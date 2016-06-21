<?php
/**
* getfinancing validation page, Prestashop 1.4 don't allow ModuleFrontController
*
* @category payment
* @author    Victor Lopez <victor.lopez@yameveo.com>
* @copyright Yameveo http://www.yameveo.com
* @license   http://www.yameveo.com/license
*/

//$path = $_SERVER['DOCUMENT_ROOT'];
$path = getcwd()."/../../";
include("{$path}/config/config.inc.php");
include("{$path}/init.php");

require_once('./getfinancing.php');
$getfinancing = new GetFinancing();
// Recoger datos de respuesta
$module_name = $getfinancing->displayName;
$currency_id = (int)Context::getContext()->currency->id;

//$json = file_get_contents('php://input');
$json =  Tools::file_get_contents('php://input');
//$data = json_decode($json, true);
$data = Tools::jsonDecode($json);

/*
  convert the merchant_transaction_id to cart id
 */

 $cart_id = 0;
 $sql = 'SELECT cart_id FROM '._DB_PREFIX_.'getfinancing where merchant_transaction_id = "'.$data->merchant_transaction_id.'"';
 if ($results = Db::getInstance()->ExecuteS($sql))
 {
   foreach ($results as $row)
   {
       $cart_id = $row['cart_id'];
   }
 }

if ($cart_id <= 0){
    die(Tools::displayError('Fatal Error: Cart Id not found'));
}

if ($data->updates->status == 'approved') {
    $cart = new Cart((int)$cart_id);
    $customer = new Customer((int)$cart->id_customer);
    $secure_key = $customer->secure_key;

    $payment_status = Configuration::get('PS_OS_PAYMENT');
    $message = 'GetFinacing payment received with request_token: '.$data->request_token;
    $order_total=$cart->getOrderTotal();
    $getfinancing->validateOrder(
        $cart_id,
        $payment_status,
        $order_total,
        $module_name,
        $message,
        array('transaction_id' => $cart_id),
        $currency_id,
        false,
        $secure_key
    );
}
