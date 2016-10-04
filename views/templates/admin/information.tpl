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

<div class="panel" style="margin-bottom: 20px">
    <h3><i class="icon icon-credit-card"></i> {l s='GetFinancing' mod='getfinancing'}</h3>
    <div class="row getfinancing-header">
        <div class="col-xs-6 col-md-4">
            <p><strong>{l s='GetFinancing - Purchase Finance Gateway' mod='getfinancing'}</strong></p><br />
            <p>
                {l s='GetFinancing is an online Purchase Finance Gateway. Choose GetFinancing as your Prestashop payment gateway to get access to multiple lenders in a powerful platform.' mod='getfinancing'}
                 <br />

                 <br />

                <a target="_blank" href="https://partner.getfinancing.com/partner/portal" class="btn btn-default" title="Login for your GetFinancing Account"><i class="icon-user"></i> {l s='Login for your GetFinancing Account' mod='getfinancing'}</a>
                <a target="_blank" href="https://www.getfinancing.com/docs" class="btn btn-default" title="Documentation">{l s='Documentation' mod='getfinancing'}</a>
            </p>
        </div>
        <div class="col-xs-12 col-md-4 pull-right text-right">
            <img src="{$module_dir|escape:'html':'UTF-8'}views/img/logo-200x200.png" class="col-xs-6 col-md-4 pull-right" id="payment-logo" />
        </div>
    </div>
</div>

{if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}
    <style>
        #configuration_toolbar{
            display:none
        }
    </style>
{/if}
