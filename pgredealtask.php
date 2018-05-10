<?php
/**
 * Plugin Name: Redeal Referral Marketing
 * Plugin URI: https://www.redeal.se
 * Description: Redeal Referral Marketing
 * Version: 1.0
 * Author: Redeal STHLM AB
 * Author URI: https://www.redeal.se/en/get-started
 * License: GPL2
 *  @copyright 2018 Redeal Referral Marketing
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License ("AFL") v. 3.0
 */
//@ini_set('display_errors', 'on');
if (!defined('_PS_VERSION_')) {
    exit;
}

class pgredealtask extends Module
{

    public function __construct()
    {
        $this->name                   = 'pgredealtask';
        $this->tab                    = 'front_office_features';
        $this->version                = '1.0.0';
        $this->author                 = 'Redeal STHLM AB';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap              = true;
        $this->need_instance          = 0;

        parent::__construct();

        $this->displayName      = $this->l('ReDeal');
        $this->description      = $this->l('Redeal Referral Marketing');
        $this->confirmUninstall = $this->l('Are you sure you want to remove ReDeal?');
    }

    public function install()
    {

        if (!parent::install()
            || !$this->registerHook('displayFooter') || !$this->registerHook('displayHeader') || !$this->enableByDefault()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {

        if (!parent::uninstall() || !$this->deleteConfig()) {
            return false;
        }

        return true;
    }

    public function hookDisplayFooter()
    {
        $googletagmanager = trim(Configuration::get('PG_REDEAL_GTM'));
        if ($googletagmanager != '') {
            $this->context->smarty->assign(array(
                'googletagmanager' => $googletagmanager,
            ));
            return $this->display(__FILE__, 'displayFooter.tpl');

        }

    }
    public function enableByDefault()
    {
        Configuration::updateValue('PG_REDEAL_ENABLE', 1);
        return true;
    }
    public function deleteConfig()
    {
        Configuration::deleteByName('PG_REDEAL_ENABLE');
        return true;
    }
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

    public function getCatName($id_category)
    {
        $name = Db::getInstance()->getValue('
      SELECT name FROM ' . _DB_PREFIX_ . 'category_lang WHERE id_category=' . (int) $id_category . ' AND id_lang=' . (int) Configuration::get('PS_LANG_DEFAULT') . ' AND id_shop =' . (int) Configuration::get('PS_SHOP_DEFAULT'));

        return $name;
    }
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

    public function getPhone($address)
    {
        return ($address->phone != '') ? $address->phone : $address->phone_mobile;

    }
    public function hookDisplayHeader()
    {
        $googletagmanager = trim(Configuration::get('PG_REDEAL_GTM'));
        if (Configuration::get('PG_REDEAL_ENABLE') == 1) {
            $context = Context::getContext();
            if ($context->controller->php_self == 'order-confirmation') {
                $id_order = Tools::getValue('id_order');
                $this->context->smarty->assign(array(
                    'redealdata'       => Tools::jsonEncode($this->extractOrder($id_order)),
                    'googletagmanager' => $googletagmanager,
                ));
                return $this->display(__FILE__, 'dealconfirm.tpl');
            }
            return $this->display(__FILE__, 'displayHeader.tpl');

        }
    }

    public function getContent()
    {
        $sv = 'no';
        if (Tools::isSubmit('saveredealtaskconfig')) {

            Configuration::updateValue('PG_REDEAL_ENABLE', Tools::getValue('pgredealenable'));
            Configuration::updateValue('PG_REDEAL_GTM', Tools::getValue('pggtagmanager'));

            $sv = 'yes';
        }
        $this->context->smarty->assign(array('pgredealenable' => Configuration::get('PG_REDEAL_ENABLE'), 'pggtagmanager' => Configuration::get('PG_REDEAL_GTM'), 'sv' => $sv));

        return $this->display(__FILE__, 'getcontent.tpl');
    }

}
