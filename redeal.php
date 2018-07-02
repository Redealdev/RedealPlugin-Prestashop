<?php
/**
 * Redeal Module
 * 
 *  @author    Redeal SE
 *  @copyright PremiumPresta
 *  
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Create class redeal.
 */
class redeal extends Module
{

    /** @var array Use to store the configuration from database */
    public $config_values;

    /** @var array submit values of the configuration page */
    protected static $config_post_submit_values = array('saveConfig');

    public function __construct()
    {
        $this->name = 'redeal'; // internal identifier, unique and lowercase
        $this->tab = 'front_office_features'; // backend module coresponding category
        $this->version = '1.0.3'; // version number for the module
        $this->author = 'Redeal STHLM AB'; // module author
        $this->need_instance = 0; // load the module when displaying the "Modules" page in backend
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Redeal'); // public name
        $this->description = $this->l('Redeal Referral Marketing'); // public description

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); // confirmation message at uninstall

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Install this module
     * @return boolean
     */
    public function install()
    {
        include dirname(__FILE__) . '/sql/install.php';

        return parent::install() &&
                $this->initConfig() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayFooter');
    }

    /**
     * Uninstall this module
     * @return boolean
     */
    public function uninstall()
    {
        include dirname(__FILE__) . '/sql/uninstall.php';

        return Configuration::deleteByName($this->name) &&
                parent::uninstall();
    }

    
    /**
     * Set the default configuration
     * @return boolean
     */
    protected function initConfig()
    {
        $this->config_values = array(
            'enable_disable' => '0',
            'gtm_id' => '',
        );
       
        return $this->setConfigValues($this->config_values);
    }

    /**
     * Configuration page
     */
    public function getContent()
    {
        $this->config_values = $this->getConfigValues();

        $this->context->smarty->assign(array(
            'module' => array(
                'class' => get_class($this),
                'name' => $this->name,
                'displayName' => $this->displayName,
                'dir' => $this->_path
            )
        ));

        return $this->postProcess();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $output = '';
       

        switch ($this->getPostSubmitValue()) {
            /* save module configuration */
            case 'saveConfig':
                
//                echo '<pre>';
//                print_r($this->config_values);
//                echo '</pre>';
//                exit;
                $config_keys = array_keys($this->config_values);
                unset($config_keys['quote']); // language field was set

                foreach ($config_keys as $key) {
                    $this->config_values[$key] = Tools::getValue($key, $this->config_values[$key]);
                }

                if ($this->setConfigValues($this->config_values)) {
                    $output .= $this->displayConfirmation($this->l('Settings updated'));
                }

            // it continues to default

            default:
                $output .= $this->renderForm();
                break;
        }

        return $output;
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->displayName,
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable'),
                        'name' => 'enable_disable',
                        'desc' => $this->l('For Enable Or Disble Module.'), 
                        'values' => array(
                                    array(
                                            'id' => 'active_on',
                                            'value' => 1,
                                            'label' => $this->l('Enabled')
                                    ),
                                    array(
                                            'id' => 'active_off',
                                            'value' => 0,
                                            'label' => $this->l('Disabled')
                                    )
                            ),
                    ), 
                    array(
                        'label' => $this->l('GTM ID'),
                        'name' => 'gtm_id',
                        'type' => 'text',
                    ),
                    
                    
                    
                ),
                
                'submit' => array(
                    'name' => 'saveConfig',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-success pull-right'
                )
            )
        );
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&module_name=' . $this->name . '&tab_module=' . $this->tab;

        $helper->tpl_vars = array(
            'fields_value' => $this->config_values, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Get configuration array from database
     * @return array
     */
    public function getConfigValues()
    {
        return json_decode(Configuration::get($this->name), true);
    }

    /**
     * Set configuration array to database
     * @param array $config 
     * @param bool $merge when true, $config can be only a subset to modify or add additional fields
     * @return array
     */
    public function setConfigValues($config, $merge = false)
    {
        if ($merge) {
            $config = array_merge($this->getConfigValues(), $config);
        }

        if (Configuration::updateValue($this->name, json_encode($config))) {
            return $config;
        }

        return false;
    }

    /**
     * Get the action submited from the configuration page
     * @return string
     */
    protected function getPostSubmitValue()
    {
//        print_r($_POST);
//        exit;
        
        foreach (self::$config_post_submit_values as $value) {
            if (Tools::isSubmit($value)) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Determins if on the module configuration page
     * @return bool
     */
    public function isConfigPage()
    {
        return self::isAdminPage('modules') && Tools::getValue('configure') === $this->name;
    }

    /**
     * Determines if on the specified admin page
     * @param string $page
     * @return bool
     */
    public static function isAdminPage($page)
    {
        return Tools::getValue('controller') === 'Admin' . ucfirst($page);
    }

    

   /**
     * Call Display Footer template and in hook.
     */
    public function hookDisplayFooter($params)
    {
        $values = $this->getConfigValues();
        
        
        !isset($params['tpl']) && $params['tpl'] = 'displayFooter';

        $this->config_values = array('googletagmanager' => $values['gtm_id']);
        
        $this->smarty->assign($this->config_values);
        
        return $this->display(__FILE__, $params['tpl'] . '.tpl');
    } 
    
    /**
     * Call Display header and Deal Confirm template and in hook.
     */
    public function hookDisplayHeader($params)
    {
        
        $values = $this->getConfigValues();
        $context = Context::getContext();
        $referer = '';
        $campaignId = '';
        if(isset($_GET['referer'])){
            $referer = $_GET['referer'];
        }
        if(isset($_GET['campaignId'])){
            $campaignId = $_GET['campaignId'];   
        }
            
            if ($context->controller->php_self == 'order-confirmation' && $values['enable_disable'] == '1') {
                
                !isset($params['tpl']) && $params['tpl'] = 'dealconfirm';

                $id_order = Tools::getValue('id_order');
                
                $this->context->smarty->assign(array(

                    'redealdata'       => Tools::jsonEncode($this->extractOrder($id_order)),

                    'googletagmanager' => $values['gtm_id'],

                ));
                
                return $this->display(__FILE__, $params['tpl'] . '.tpl');

        }
            
        !isset($params['tpl']) && $params['tpl'] = 'displayHeader';

        $this->config_values = array('googletagmanager' => $values['gtm_id']);
        
        $this->smarty->assign(array(
                $this->config_values,
                'referer' => $referer,
                'campaignId' => $campaignId,
       ));
        
        return $this->display(__FILE__, $params['tpl'] . '.tpl');
    }
    
    /**
     * Hook to extract Order
     */
    public function extractOrder($id_order)

    {

        $order = new Order((int) $id_order);

        if (is_object($order)) {

            $customer    = new Customer($order->id_customer);

            $address     = new Address((int) $order->id_address_delivery);

            $currency    = new Currency((int) $order->id_currency);

            $lang        = new Language((int) $this->context->cookie->id_lang);

            $allproducts = $order->getProducts();

            $country     = new Country($address->id_country);



            $products = [];

            $p        = [];

            if (count($allproducts) > 0) {

                foreach ($allproducts as $product) {

                    $p['sku']      = $product['product_id'];

                    $p['price']    = $product['product_price'];

                    $p['category'] = $this->getCatName($product['id_category_default']);

                    $p['quantity'] = $product['product_quantity'];



                    $products[] = $p;

                }

            }

            $tax      = $order->total_paid_tax_incl - $order->total_paid_tax_excl;

            $dealdata = ['id' => $id_order, 'total' => $order->total_paid, 'price' => $order->total_products, 'tax' => $tax, 'shipping' => $order->total_shipping, 'currency' => $currency->iso_code, 'country' => $country->iso_code, 'language' => $lang->iso_code, 'name' => $customer->firstname, 'email' => $customer->email, 'phone' => $this->getPhone($address), 'coupons' => $this->getCoupons($id_order), 'products' => $products];

        }



        return $dealdata;

    }
    
    /*
     * Function to get Phone Details.
     */
    public function getPhone($address)
    {

        return ($address->phone != '') ? $address->phone : $address->phone_mobile;
    }
    
    /*
     * Function to get Coupons Details.
     */
    public function getCoupons($id_order)

    {

        $coupons = [];

        $sql     = 'SELECT code FROM ' . _DB_PREFIX_ . 'order_cart_rule ocr INNER JOIN ' . _DB_PREFIX_ . 'cart_rule cr ON cr.id_cart_rule = ocr.id_cart_rule WHERE ocr.id_order=' . (int) $id_order;

        $rows    = Db::getInstance()->executeS($sql);

        if (count($rows) > 0) {

            foreach ($rows as $code) {

                $coupons[] = $code['code'];

            }

        }



        return $coupons;

    }


    /*
     * Function to get Category name by ID.
     */
    public function getCatName($id_category)

    {

        $name = Db::getInstance()->getValue('

      SELECT name FROM ' . _DB_PREFIX_ . 'category_lang WHERE id_category=' . (int) $id_category . ' AND id_lang=' . (int) Configuration::get('PS_LANG_DEFAULT') . ' AND id_shop =' . (int) Configuration::get('PS_SHOP_DEFAULT'));



        return $name;

    }
}
