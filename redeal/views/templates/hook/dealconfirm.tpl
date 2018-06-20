{**
 * Plugin Name: Redeal Referral Marketing
 * Description: Redeal Referral Marketing
 * Version: 1.0
 * Author: Redeal STHLM AB
 * License: GPL2
 * @copyright 2018 Redeal Referral Marketing
 *}
 
 {*
  *This template will call when orde will be confirmed and below code is use for call redeal.js and display popup in order confirmation page.
 *}
{if $page_name=='order-confirmation'}
 	<script> 
 	{literal}   
    (function(i,s,o,g,r,a,m){i['RedealObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window, document, 'script', window.location.protocol + '//widget.redeal.se/js/redeal.js', 'redeal');
{/literal}
    var redeal_data = {$redealdata};
    redeal_data.event = "orders";
    window.dataLayer = window.dataLayer || [];
    
    dataLayer.push(redeal_data);


redeal('checkout', {$redealdata});
console.log({$redealdata});
</script>

 {/if}