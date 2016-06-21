<?php
/**
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
*/

include(dirname(__FILE__).'/../../config/config.inc.php');

if (version_compare(_PS_VERSION_, "1.5", "<")) {
    include(dirname(__FILE__).'/../../header.php');
      $context = Context::getContext();
      $status  = Tools::getValue('status');
    if ($status == "ok") {
          $cart_id = Tools::getValue('c');
          $order_id = Order::getOrderByCartId($cart_id);
          $order = new Order($order_id);
          $context->smarty->assign(array('total' => $order->total_paid));
          $context->smarty->display(_PS_MODULE_DIR_.'getfinancing/views/templates/front/confirmation.tpl');
    } else {
          $context->smarty->display(_PS_MODULE_DIR_.'getfinancing/views/templates/front/error.tpl');
    }
    include(dirname(__FILE__).'/../../footer.php');
} else {
      Tools::redirect(
          __PS_BASE_URI__ .
          'index.php?fc=module&module=getfinancing&controller=confirmation&' .
          http_build_query($_GET)
      );
}

//include(dirname(__FILE__).'/../../footer.php');
