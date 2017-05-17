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

//clean code
//if (!defined('_PS_VERSION_'))
//    exit;

class GetFinancing extends PaymentModule
{
    protected $output = '';
    public static $modulePath;

    public function __construct()
    {
        $this->name = 'getfinancing';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.3';
        $this->author = 'getfinancing';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        //$this->module_key = '2b9bc901b4d834bb7069e7ea6510438f';
        $this->gateway_url_prod  = "https://api.getfinancing.com";
        $this->gateway_url_stage = "https://api-test.getfinancing.com";

        self::initModuleAccess();

        parent::__construct();

        $this->displayName = $this->l('GetFinancing');
        $this->description = $this->l('Take payments via GetFinancing Platform - Purchase Finance Gateway.');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, "1.5", "<")) {
            require(_PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php');
        }
    }

    public function install()
    {
        Configuration::updateValue('GETFINANCING_ENVIRONMENT', 0);
        Configuration::updateValue('GETFINANCING_USERNAME', '');
        Configuration::updateValue('GETFINANCING_PASSWORD', '');
        Configuration::updateValue('GETFINANCING_MERCHANT_ID', '');
        //Configuration::updateValue('GETFINANCING_CURRENCY', 'EUR');
        Configuration::updateValue('GETFINANCING_MIN_AMOUNT', 100);


        if (version_compare(_PS_VERSION_, "1.5", ">=")) {
            $this->registerHook('displayPaymentEU');
        }

        /*
          getfinancing table to store relation with cart id and encrypted token
         */

         $this->_createGFTable();

        return parent::install() &&
                $this->registerHook('header') &&
                $this->registerHook('payment') &&
                $this->registerHook('paymentReturn') &&
                $this->registerHook('footer');
    }

    private function _createGFTable()
    {
      $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'getfinancing` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `cart_id` int(10) NOT NULL,
          `merchant_transaction_id` varchar(128) NOT NULL,
          PRIMARY KEY (`id`)
          ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
          $result &= Db::getInstance()->execute($sql);
    }

    public function uninstall()
    {
        Configuration::deleteByName('GETFINANCING_ENVIRONMENT');
        Configuration::deleteByName('GETFINANCING_USERNAME');
        Configuration::deleteByName('GETFINANCING_PASSWORD');
        Configuration::deleteByName('GETFINANCING_MERCHANT_ID');
        //Configuration::deleteByName('GETFINANCING_CURRENCY');
        Configuration::deleteByName('GETFINANCING_MIN_AMOUNT');

        return parent::uninstall();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitGetFinancingSettings')) {
            $error = '';
            Configuration::updateValue('GETFINANCING_ENVIRONMENT', Tools::getValue('GETFINANCING_ENVIRONMENT'));
            Configuration::updateValue('GETFINANCING_USERNAME', Tools::getValue('GETFINANCING_USERNAME'));
            Configuration::updateValue('GETFINANCING_PASSWORD', Tools::getValue('GETFINANCING_PASSWORD'));
            Configuration::updateValue('GETFINANCING_MERCHANT_ID', Tools::getValue('GETFINANCING_MERCHANT_ID'));
            //Configuration::updateValue('GETFINANCING_CURRENCY', Tools::getValue('GETFINANCING_CURRENCY'));

            if (!Validate::isInt(Tools::getValue('GETFINANCING_MIN_AMOUNT')))
                $error .= $this->l('The minimun amount must be integer.');
            else
                Configuration::updateValue('GETFINANCING_MIN_AMOUNT', Tools::getValue('GETFINANCING_MIN_AMOUNT'));

            if ($error != '') {
                $this->output .= $this->displayError($error);
            } else {
                $this->output .= $this->displayConfirmation($this->l('The settings updated ok.'));
            }
        }
    }

    public function getContent()
    {
        $this->postProcess();

        $this->context->smarty->assign('module_dir', $this->_path);

        if (version_compare(_PS_VERSION_, "1.5", "<")) {
            $this->context->smarty->assign(
                array(
                'formAction' => $_SERVER['REQUEST_URI'],
                'formConfigValues' => $this->getConfigFormValues(),
                'selectValues' => array(0, 1),
                'outputEnvironment' => array('TEST', 'REAL'),
                'outputDiscount' => array('FALSE', 'TRUE'),
                'selectValuesDiscount' => array(0, 1),
                )
            );
            $this->output .= $this->display(__FILE__, 'views/templates/admin/config_14.tpl');
        } else {
            $this->output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/information.tpl');
            $this->output .= $this->displayFormSettings();
        }


        return $this->output;
    }

    public function displayFormSettings()
    {

        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'getfinancing';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        //$helper->toolbar_btn = $this->initToolbar();
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitGetFinancingSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('GetFinancing settings')
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'name' => 'GETFINANCING_ENVIRONMENT',
                    'is_bool' => true,
                    'label' => $this->l('Choose environment'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id_env' => 0,
                                'name' => $this->l('Test')
                            ),
                            array(
                                'id_env' => 1,
                                'name' => $this->l('Real')
                            )
                        ),
                        'id' => 'id_env',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('GetFinancing Username'),
                    'name' => 'GETFINANCING_USERNAME',
                    'required' => true,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('GetFinancing Password'),
                    'name' => 'GETFINANCING_PASSWORD',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Merchant ID'),
                    'name' => 'GETFINANCING_MERCHANT_ID',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Minimum amount'),
                    'name' => 'GETFINANCING_MIN_AMOUNT',
                    'desc' => $this->l('Cart minimum amount to GetFinancing.'),
                    'required' => false,
                    'lang' => false,
                    'col' => 2,
                ),
            ),
            'submit' => array(
                'name' => 'submitGetFinancingSettings',
                'title' => $this->l('Save')
            ),
        );

        $helper->fields_value['GETFINANCING_ENVIRONMENT'] = Configuration::get('GETFINANCING_ENVIRONMENT');
        $helper->fields_value['GETFINANCING_USERNAME'] = Configuration::get('GETFINANCING_USERNAME');
        $helper->fields_value['GETFINANCING_PASSWORD'] = Configuration::get('GETFINANCING_PASSWORD');
        $helper->fields_value['GETFINANCING_MERCHANT_ID'] = Configuration::get('GETFINANCING_MERCHANT_ID');
        //$helper->fields_value['GETFINANCING_CURRENCY'] = Configuration::get('GETFINANCING_CURRENCY');
        $helper->fields_value['GETFINANCING_MIN_AMOUNT'] = Configuration::get('GETFINANCING_MIN_AMOUNT');

        return $helper->generateForm($this->fields_form);
    }


    /**
     * Retrocompatibility PS 1.4 get config values
     * @return array
     */
    protected function getConfigFormValues()
    {
        return array(
            'GETFINANCING_ENVIRONMENT' => Configuration::get('GETFINANCING_ENVIRONMENT'),
            'GETFINANCING_USERNAME' => Configuration::get('GETFINANCING_USERNAME'),
            'GETFINANCING_PASSWORD' => Configuration::get('GETFINANCING_PASSWORD'),
            'GETFINANCING_MERCHANT_ID' => Configuration::get('GETFINANCING_MERCHANT_ID'),
            'GETFINANCING_MIN_AMOUNT' => Configuration::get('GETFINANCING_MIN_AMOUNT')
        );
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookFooter($params)
    {
        return false;
        //TO FILL IF NEEDED
        //$this->context->controller->addJS(
        //    'https://cdn.pagamastarde.com/pmt-simulator/3/js/pmt-simulator.min.js'
        //);
    }

    public function hookPayment($params)
    {
        if ($this->context->cart->getOrderTotal() < Configuration::get('GETFINANCING_MIN_AMOUNT'))
            return;

        $customer = new Customer((int)$params['cart']->id_customer);
        $cart_products = $this->context->cart->getProducts();

        $convert_price = Tools::convertPrice(
            $this->context->cart->getOrderTotal(true, 3),
            $this->context->currency
        );

        $amount = number_format(
            $convert_price/100,
            2,
            '.',
            ''
        );
        $amount = str_replace('.', '', $amount);


        $desciption=array();
        $cart_items = array();
        foreach ($cart_products as $p) {
             $desciption[]=  $p['name']. " (".$p['cart_quantity'].")";
             $cart_items[] = array(
                                'sku' => $p['name'],
                                'display_name' => $p['name'],
                                'unit_price' => number_format($p['total'], 2),
                                'quantity' => $p['quantity'],
                                'unit_tax' => $p['ecotax'] 
             );
        }

        if (version_compare(_PS_VERSION_, "1.5", "<")) {
            $shippingCost = $this->context->cart->getOrderShippingCost();
        } else {
            $shippingCost = $this->context->cart->getTotalShippingCost(null, true, null);
        }

        $url_OK = $this->getPagantisLink('confirmation.php', array('status'=>'ok', 'c' => $this->context->cart->id));
        $url_NOK = $this->getPagantisLink('confirmation.php', array('status'=>'ko'));

        if ($shippingCost > 0) {
            $desciption[]= $this->l('Shipping cost');
        }

        $description = implode(',', $desciption);

        //address
        $address = new Address($this->context->cart->id_address_delivery);
        $street=$address->address1.' '.$address->address2;
        $city=$address->city;
        $user_state = new State($address->id_state);
        $province=$user_state->iso_code;
        $zipcode=$address->postcode;
        $phone = $address->phone;
        $mobile_phone = $address->phone_mobile;
        $desciption="test";
        $email = ($this->context->cookie->logged ? $this->context->cookie->email : $customer->email);

        /*
          store the relation with cart id and token
         */
         $merchant_loan_id = md5(mktime() . Configuration::get('GETFINANCING_USERNAME') . $email . $amount);
         $insert_data = array(
          'cart_id' => $this->context->cart->id,
          'merchant_transaction_id' => $merchant_loan_id);

         $result = Db::getInstance()->insert('getfinancing', $insert_data, $null_values = false, $use_cache = true, $type = Db::INSERT, $add_prefix = true);

        $gf_data = array(
            'amount'           => $amount,
            //'product_info'     => $desciption,
            'cart_items'       => $cart_items,
            'first_name'       => ($this->context->cookie->logged ?
              $this->context->cookie->customer_firstname :  $customer->firstname),
            'last_name'        => ($this->context->cookie->logged ?
              $this->context->cookie->customer_lastname :  $customer->lastname),
            'shipping_address' => array(
                'street1'  => $street,
                'city'    => $city,
                'state'   => $province,
                'zipcode' => $zipcode
            ),
            'billing_address' => array(
                'street1'  => $street,
                'city'    => $city,
                'state'   => $province,
                'zipcode' => $zipcode
            ),
            'email'            => $email,
            'merchant_loan_id' => (string)$merchant_loan_id,
            'version' => '1.9'
        );
        $body_json_data = json_encode($gf_data);
        $header_auth = base64_encode(Configuration::get('GETFINANCING_USERNAME') . ":" . Configuration::get('GETFINANCING_PASSWORD'));

        if (Configuration::get('GETFINANCING_ENVIRONMENT') == 1) {
            $url_to_post = $this->gateway_url_stage;
        } else {
            $url_to_post = $this->gateway_url_prod;
        }

        $url_to_post .= '/merchant/' . Configuration::get('GETFINANCING_MERCHANT_ID')  . '/requests';

        $post_args = array(
            'body' => $body_json_data,
            'timeout' => 60,     // 60 seconds
            'blocking' => true,  // Forces PHP wait until get a response
            'sslverify' => false,
            'headers' => array(
              'Content-Type' => 'application/json',
              'Authorization' => 'Basic ' . $header_auth,
              'Accept' => 'application/json'
             )
        );
//        echo "<pre>"; print_r ($post_args); die();
        $gf_response = $this->_remote_post( $url_to_post, $post_args );
        $response_body = json_decode($gf_response);
        if (!isset($response_body->href)) {
          $error="GetFinancing cannot process your order. Please try again or select a different payment method.";
          return false;
          //if we want to show an error when gf is not loaded use:
          return  $this->displayError($error);
        }

        //dynamic CallbackFilterIterator
        $callback_url= $this->getPagantisCallbackUrl('validation.php', array());

        $this->smarty->assign(array(
            'url_OK' => $url_OK,
            'url_NOK' => $url_NOK,
            'href' => $response_body->href,
            'version4' => version_compare(_PS_VERSION_, "1.5", "<"),
        ));

        return $this->display(__FILE__, 'views/templates/front/payment.tpl');
    }

    public function hookDisplayPaymentEU($params)
    {
        return $this->hookPayment($params);
    }

    /**
     * Not used because pagantis redirect return to ok_url & nok_url
     * @param $params
     * @return mixed
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false) {
            return;
        }

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR')) {
             $this->smarty->assign('status', 'ok');
        }

        $this->smarty->assign(array(
                'id_order' => $order->id,
                'reference' => $order->reference,
                'params' => $params,
                'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/front/confirmation.tpl');
    }

    /**
     * Retrocomatibility prestashop 1.4, it's necesary file path because doesn't exists ModuleFrontController
     * @param $file
     * @param array $params
     * @return string
     */
    public function getPagantisLink($file, array $params = array())
    {
            return Tools::getShopDomainSsl(true)._MODULE_DIR_.$this->name.'/'
                .$file.'?'.htmlspecialchars_decode(http_build_query($params));
    }

    /**
     * Retrocomatibility prestashop 1.4, it's necesary file path because doesn't exists ModuleFrontController
     * @param $file
     * @param array $params
     * @return string
     */
    public function getPagantisCallbackUrl($file, array $params = array())
    {
            return Tools::getShopDomainSsl(true)._MODULE_DIR_.$this->name.'/'.$file.'?'.http_build_query($params);
    }

    public static function initModuleAccess()
    {
        getfinancing::$modulePath = _PS_MODULE_DIR_.'getfinancing/';
    }

    /**
     * Set up RemotePost / Curl.
     */
    function _remote_post($url,$args=array()) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args['body']);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Magento - GetFinancing Payment Module ');
        if (defined('CURLOPT_POSTFIELDSIZE')) {
            curl_setopt($curl, CURLOPT_POSTFIELDSIZE, 0);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, $args['timeout']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $array_headers = array();
        foreach ($args['headers'] as $k => $v) {
            $array_headers[] = $k . ": " . $v;
        }
        if (sizeof($array_headers)>0) {
          curl_setopt($curl, CURLOPT_HTTPHEADER, $array_headers);
        }

        if (strtoupper(substr(@php_uname('s'), 0, 3)) === 'WIN') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $resp = curl_exec($curl);
        curl_close($curl);

        if (!$resp) {
          return false;
        } else {
          return $resp;
        }
    }
}
