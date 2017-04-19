<?php 

/**
* Project: Modulo de Boleto Bancario - PagHiper 
*
* @author Alphacode - IT Solutions <www.alphacode.com.br>
*
*/

$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `cpf_cnpj_paghiper` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `cpf_cnpj_paghiper` VARCHAR( 255 ) NOT NULL;

");
$installer->endSetup();