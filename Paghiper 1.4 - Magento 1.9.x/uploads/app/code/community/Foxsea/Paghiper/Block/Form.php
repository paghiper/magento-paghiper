<?php

class Foxsea_Paghiper_Block_Form extends Mage_Payment_Block_Form {
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('foxsea/paghiper/payment/form/paghiper.phtml');
    }
}
