{**
 * DESCRIPTION.
 *
 * RedealPlugin-Prestashop
 *
 *  @author    Redeal
 *  @copyright 2018 Redeal
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License ("AFL") v. 3.0
 *}
{if isset($googletagmanager)}
{if $googletagmanager neq ''}
<script>
  var pggoogletmanager = "{$googletagmanager|escape:'htmlall':'utf-8'}";
</script>
{literal}
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',pggoogletmanager);</script>
<!-- End Google Tag Manager -->
{/literal}
{/if}
{/if}
{if $page_name=='index'}
{literal}
 <script type="text/javascript">
 	(function(i,s,o,g,r,a,m){i['RedealObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window, document, 'script', window.location.protocol + '//widget.redeal.se/js/redeal.js', 'redeal');

 </script>
 {/literal}
 {/if}
