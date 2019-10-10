<?php

class Foxsea_Paghiper_Block_Info extends Mage_Payment_Block_Info {
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('foxsea/paghiper/payment/info/paghiper.phtml');
    }
}
