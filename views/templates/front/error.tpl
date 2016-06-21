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

{capture name=path}{l s='Payment error' mod='getfinancing'}{/capture}

{if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}{include file="$tpl_dir./breadcrumb.tpl"}{/if}

<h2 style="font-style: normal;">{l s='Your payment could not be completed' mod='getfinancing'}</h2><br />


<p>
{l s='We are sorry, but your payment could not be successfully completed. You can try again or choose another payment method.' mod='getfinancing'}
</p>

<p>
{l s='There are several reasons for this to happen:' mod='getfinancing'}
  <ul>
    <li>- {l s='You mistook any of the digits of your credit card. Make sure you entered them correctly.' mod='getfinancing'}</li>
    <li>- {l s='Make sure your credit card is not expired and has been blocked.' mod='getfinancing'}</li>
    <li>- {l s='There has been a problem with our payment gateway provider.' mod='getfinancing'}</li>
  </ul>
</p>

<p>
{l s='In any case, you can contact us by mail or by phone and we will try to fix your problem together.' mod='getfinancing'}
</p>

<br />

{if $cart_qties > 0}
    {if version_compare($smarty.const._PS_VERSION_,'1.5.0.0','<')}
        <a href="{$link->getPageLink('order', true)|escape:'htmlall':'UTF-8'}.php"  style="text-transform: uppercase; border: 1px solid green; background-color: green; font-size: 13px; font-weight: bold; color: white; padding: 5px; float: left; margin-top: 20px;">{l s='Try again' mod='getfinancing'}</a>
    {else}
        <a href="{$link->getPageLink('order', true)|escape:'htmlall':'UTF-8'}"  style="text-transform: uppercase; border: 1px solid green; background-color: green; font-size: 13px; font-weight: bold; color: white; padding: 5px; float: right; margin-top: 20px;">{l s='Try again' mod='getfinancing'}</a>
    {/if}

{/if}
