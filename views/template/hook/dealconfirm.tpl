{**
 * Plugin Name: Redeal Referral Marketing
 * Plugin URI: https://www.redeal.se
 * Description: Redeal Referral Marketing
 * Version: 1.0
 * Author: Redeal STHLM AB
 * Author URI: https://www.redeal.se/en/get-started
 * License: GPL2
 *  @copyright 2018 Redeal Referral Marketing
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License ("AFL") v. 3.0
 *}
{if $page_name=='order-confirmation'}
 	<script> 
 	{literal}   
    (function(i,s,o,g,r,a,m){i['RedealObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window, document, 'script', window.location.protocol + '//widget.redeal.se/js/redeal.js', 'redeal');
{/literal}
redeal('checkout', {$redealdata});
</script>

 {/if}