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

{capture name=path}{l s='GetFinancing confirmation' mod='getfinancing'}{/capture}
{if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}{include file="$tpl_dir./breadcrumb.tpl"}{/if}

<h3>{l s='Your order is complete.' mod='getfinancing'}</h3>
<p>
    <br />- {l s='Amount' mod='getfinancing'} : <span class="price"><strong>{$total|round:"22"|escape:'htmlall':'UTF-8'}{$currency->sign|escape:'html':'UTF-8'}</strong></span>
    <br /><br />{l s='An email has been sent with this information.' mod='getfinancing'}
    <br /><br />{l s='If you have questions, comments or concerns, please contact our expert customer support team.' mod='getfinancing'}
</p>
