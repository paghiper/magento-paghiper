<?php

class Foxsea_Paghiper_BoletoController extends Mage_Adminhtml_Controller_Action
{

    public function _isAllowed(){
        return true;
    }

    public function generateAction(){
        $order_id = $this->getRequest()->getParam('order_id');
        $data = $this->helper()->createOrderArray($order_id);

        $generate = $this->helper()->generate($data);

        if(isset($generate['success']) && $generate['success']){
            if($generate['success']){
                $this->helper()->addInformation($order, $generate['additional']);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess('Boleto gerado com sucesso!');
            return $this->_redirectReferer();
        }else{
            Mage::getSingleton('adminhtml/session')->addError('Problema ao gerar boleto: ' . $generate['message']);
            return $this->_redirectReferer();
        }
    }

    protected function helper(){
        return Mage::helper('foxsea_paghiper/order');
    }

}
