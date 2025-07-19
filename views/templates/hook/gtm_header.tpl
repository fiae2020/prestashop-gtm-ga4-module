{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

{* Google Tag Manager with Optional GA4 Configuration *}
<script>
  // Initialize dataLayer
  window.dataLayer = window.dataLayer || [];

  window.dataLayer.push({
    'gtm.start': new Date().getTime(),
    'event': 'gtm.js'
  });

  {if $has_ga4}
  // Optionally add GA4-specific metadata
  window.dataLayer.push({
    'event': 'ga4_init',
    'ga4_measurement_id': '{$ga4_measurement_id}',
    'page_title': document.title,
    'page_location': window.location.href
  });
  {/if}

  // Load GTM container
  {literal}
  (function(w,d,s,l,i){
    var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),
        dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;
    j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
    f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','{/literal}{$gtm_container_id}{literal}');
  {/literal}
</script>
