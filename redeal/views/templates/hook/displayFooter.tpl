{**
 * Plugin Name: Redeal Referral Marketing
 * Description: Redeal Referral Marketing
 * Version: 1.0
 * Author: Redeal STHLM AB
 * License: GPL2
 * @copyright 2018 Redeal Referral Marketing
 *}
 
 {*
  *This template is call in footer of site
 *}

{if isset($googletagmanager)}

{if $googletagmanager neq ''}

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$googletagmanager|escape:'htmlall':'utf-8'}"

height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

{/if}

{/if}
