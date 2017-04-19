<?php 

/**
* Project: Modulo de Boleto Bancario - PagHiper 
*
* @author Alphacode - IT Solutions <www.alphacode.com.br>
*
*/ 

class Alphacode_Paghiper_Block_Info_Paghiper extends Mage_Payment_Helper_Data 
	{
		  function getPaymentGatewayUrl() 
		  {
		    return Mage::getUrl('paghiper/payment/gateway', array('_secure' => false));
		  }

	}
 ?>