{**
 * DESCRIPTION.
 *
 * RedealPlugin-Prestashop
 *
 *  @author    Redeal
 *  @copyright 2018 Redeal
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