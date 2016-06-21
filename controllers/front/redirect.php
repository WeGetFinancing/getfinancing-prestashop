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

class GetFinancingRedirectModuleFrontController extends ModuleFrontController
{
  /**
    * Do whatever you have to before redirecting the customer on the website of your payment processor.
    */
    public function postProcess()
    {
        /**
         * Oops, an error occured.
         */
        if (Tools::getValue('action') == 'error') {
            return $this->displayError('An error occurred while trying to redirect the customer');
        } else {
            $this->context->smarty->assign(array(
                'cart_id' => Context::getContext()->cart->id,
                'secure_key' => Context::getContext()->customer->secure_key,
            ));

            return $this->setTemplate('redirect.tpl');
        }
    }

    protected function displayError($message, $description = false)
    {
        /**
         * Create the breadcrumb for your ModuleFrontController.
         */
        $the_link = $this->context->link->getPageLink('order', null, null, 'step=3');
        $the_module = $this->module->l('Payment');
        $the_error = $this->module->l('Error');
        $this->context->smarty->assign(
            'path',
            '<a href="'.$the_link.'">'-$the_module.'</a><span class="navigation-pipe">&gt;</span>'.$the_error
        );

        /**
         * Set error message and description for the template.
         */
        array_push($this->errors, $this->module->l($message), $description);

        return $this->setTemplate('error.tpl');
    }
}
