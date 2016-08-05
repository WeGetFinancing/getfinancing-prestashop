{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


{if $version4}
<script src="https://cdn.pagamastarde.com/pmt-simulator/3/js/pmt-simulator.min.js"></script>
    <p class="payment_module">
      <a href="javascript:fireGF();" title="{l s='GetFinancing' mod='getfinancing'}">
        <img id="logo_pagamastarde" src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/logo-64x64.png"
        alt="{l s='Logo Paga Mas Tarde' mod='getfinancing'}" style="max-width: 80px"/>
          {l s='GetFinancing' mod='getfinancing'}

      </a>
    </p>


{else}
    <div class="row">
        <div class="col-xs-12">
            {if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}
                <div class="payment_module">
                      <a href="javascript:fireGF();" title="{l s='GetFinancing' mod='getfinancing'}">
                        <img id="logo_pagamastarde" src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/logo-64x64.png"
                          alt="{l s='Logo Paga Mas Tarde' mod='getfinancing'}" style="max-width: 80px"/>
                        {l s='GetFinancing' mod='getfinancing'}

                    </a>
                </div>
            {else}
                <p class="payment_module" id="getfinancing_payment_button">
                  <a href="javascript:fireGF();" title="{l s='GetFinancing' mod='getfinancing'}">
                    {l s='GetFinancing' mod='getfinancing'}

                    </a>
                </p>

            {/if}
        </div>
    </div>
{/if}


<script type="text/javascript" src="https://cdn.getfinancing.com/libs/1.0/getfinancing.js"></script>

      <script type="text/javascript">

          function fireGF() {
            new GetFinancing("{$href|escape:'htmlall':'UTF-8'}", onComplete, onAbort);
          }

          var onComplete = function() {
              window.location.href="{$url_OK}";
          };

          var onAbort = function() {
              window.location.href="{$url_NOK|escape:'htmlall':'UTF-8'}";
          };

      </script>
