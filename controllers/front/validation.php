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

class GetFinancingValidationModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {

        if (!Tools::getValue('redirect')) {
            $module_name = $this->module->displayName;
            $currency_id = (int)Context::getContext()->currency->id;

            //$json = file_get_contents('php://input');
            $json =  Tools::file_get_contents('php://input');
            //$data = json_decode($json, true);
            $data = Tools::jsonDecode($json);

            //validate the callback
            if (Configuration::get('GETFINANCING_ENVIRONMENT') == 1) {
                  $key_to_use = Configuration::get('GETFINANCING_ACCOUNT_KEY_LIVE');
            } else {
                  $key_to_use = Configuration::get('GETFINANCING_ACCOUNT_KEY_TEST');
            }
            $signature_check = sha1(
                $key_to_use .
                $data->account_id .
                $data->api_version .
                $data->event .
                $data->data->id
            );
            if ($signature_check != $data->signature) {
                //hack detected - not validate order
                die(Tools::displayError('Fatal Error: Callback signature incorrect'));
            }

            $order_id = $data->data->order_id;
            $cart_id = $order_id;

            if ($data->event == 'charge.created') {
                $cart = new Cart((int)$cart_id);
                $customer = new Customer((int)$cart->id_customer);
                $secure_key = $customer->secure_key;

                $payment_status = Configuration::get('PS_OS_PAYMENT');
                $message = null;

                //$order_total=$cart->getOrderTotal();
                $order_total = $data->data->amount / 100;

                $this->module->validateOrder(
                    $cart_id,
                    $payment_status,
                    $order_total,
                    $module_name,
                    $message,
                    array(),
                    $currency_id,
                    false,
                    $secure_key
                );
                $redirect_uri = 'index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='
                  .$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$secure_key;
                Tools::redirect($redirect_uri);
            } else {
                //nothing happen
            }
        } else {
          //nothing happen
        }
    }
}
