<?php

class Foxsea_Paghiper_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getApiUrl(){
        return 'https://api.paghiper.com/';
    }

    public function getApiKey(){
        return Mage::getStoreConfig('payment/foxsea_paghiper/apikey');
    }

    public function getToken(){
        return Mage::getStoreConfig('payment/foxsea_paghiper/token');
    }

    public function getConfig($config){
        return Mage::getStoreConfig('payment/foxsea_paghiper/' . $config);
    }

    public function getNotificationUrl(){
        return Mage::getUrl('paghiper/order/update');
    }

}
