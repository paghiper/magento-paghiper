<?php

class Foxsea_Paghiper_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract {

    protected $_canOrder            = true;
    protected $_allowCurrencyCode   = ['BRL'];

    protected $_code                = 'foxsea_paghiper';
    protected $_formBlockType       = 'foxsea_paghiper/form';
    protected $_infoBlockType       = 'foxsea_paghiper/info';

    public function getInformation() {
        return $this->getConfigData('information');
    }

    // gera o boleto
    public function order(Varien_Object $payment, $amount)
    {
        if($this->canOrder()){
            $order = $this->getInfoInstance()->getOrder();
            $data = $this->helper()->createOrderArray($order->getIncrementId());
            $generate = $this->helper()->generate($data);
            if($generate['success']){
                $this->helper()->addInformation($order, $generate['additional']);
            }
        }
    }

    protected function helper(){
        return Mage::helper('foxsea_paghiper/order');
    }

}
