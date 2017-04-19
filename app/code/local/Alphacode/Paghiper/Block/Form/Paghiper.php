<?php 

/**
* Project: Modulo de Boleto Bancario - PagHiper 
*
* @author Alphacode - IT Solutions <www.alphacode.com.br>
*
*/

class Alphacode_Paghiper_Block_Form_Paghiper extends Mage_Payment_Block_Form
{
  protected function _construct(){
	if (Mage::getSingleton('customer/session')->isLoggedIn()) {
	    $customer = Mage::getSingleton('customer/session')->getCustomer();
	    $customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
	    Mage::log($customerData);
	}	

	if (isset($customerData["taxvat"]) && strlen($customerData["taxvat"]) > 10){
		$cpf_cnpj = preg_replace( '/[^0-9]/is', '', $customerData["taxvat"]); 
	} else {
		/* CARREGA CPF OU CNPJ INFORMADO NO ULTIMO PEDIDO */
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$customer_id = $customer->entity_id;

		if(isset($customer_id) && $customer_id > 0){
			$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
			$query      = "SELECT * FROM `sales_flat_order` WHERE customer_id = " . $customer_id . " ORDER BY quote_id DESC LIMIT 1";
			$rows       = $connection->fetchAll($query);
		}

		if (isset($rows[0]['quote_id']) && $rows[0]['quote_id'] > 0){
			$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
			$query      = "SELECT * FROM `sales_flat_quote_payment` WHERE quote_id = " . $rows[0]['quote_id'];
			$rows       = $connection->fetchAll($query);

			$cpf_cnpj = $rows[0]['cpf_cnpj_paghiper'];
		} else {
			$cpf_cnpj = "";
		}

	}

    parent::_construct();
    $this->setData('cpf_cnpj', $cpf_cnpj)->setTemplate('paghiper/Form/paghiper.phtml');
  }
}