<?php

class Foxsea_Paghiper_Helper_Order extends Mage_Core_Helper_Abstract {

    public function createOrderArray($order_id){
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        if($order && $order->getId()){
            $address = $order->getBillingAddress();

            $discount_payment = 0;
            $discount_days = 0;
            $early_payment = $this->helper()->getConfig('early_payment_discount');

            if($early_payment == 1){
                $discount_payment = $this->helper()->getConfig('early_payment_discounts_cents');
                if($discount_payment != '' && intval($discount_payment) >= 1){
                    $discount_payment = $order->getGrandTotal() * ($discount_payment / 100);
                    $discount_payment = intval($discount_payment * 100);
                }
                $discount_days = $this->helper()->getConfig('early_payment_discounts_days');
            }

            $taxvat = (Mage::app()->getRequest()->getParam('paghiper_taxvat') != '') ? Mage::app()->getRequest()->getParam('paghiper_taxvat') : $order->getCustomerTaxvat();

            $data = [
                'order_id' => $order->getIncrementId(),
                'payer_email' => $order->getCustomerEmail(),
                'payer_name' => $order->getCustomerName(),
                'payer_cpf_cnpj' => $taxvat,
                'payer_phone' => $address->getTelephone(),
                'payer_street' => (isset($address->getStreet()[0])) ? $address->getStreet()[0] : '',
                'payer_number' => (isset($address->getStreet()[1])) ? $address->getStreet()[1] : '',
                'payer_complement' => (isset($address->getStreet()[2])) ? $address->getStreet()[2] : '',
                'payer_district' => (isset($address->getStreet()[3])) ? $address->getStreet()[3] : '',
                'payer_city' => $address->getCity(),
                'payer_zip_code' => $address->getPostcode(),
                'discount_cents' => ($order->getDiscountAmount()*-1) * 100,
                'shipping_price_cents' => $order->getShippingAmount() * 100,
                'shipping_methods' => $order->getShippingDescription(),
                'fixed_description' => false,
                'days_due_date' => $this->helper()->getConfig('days_due_date'), // vencimento
                'late_payment_fine' => $this->helper()->getConfig('late_payment_fine'), // percentual multa
                'per_day_interest' => $this->helper()->getConfig('per_day_interest'), // juros por atraso (bool)
                'early_payment_discounts_cents' => $discount_payment,
                'early_payment_discounts_days' => $discount_days,
                'open_after_day_due' => $this->helper()->getConfig('open_after_day_due'),
                'notification_url' => $this->helper()->getNotificationUrl(),
                'type_bank_slip' => 'boletoA4',
                'partners_id' => 'N2DXMMU6',
                'items' => []
            ];

            foreach ($order->getAllVisibleItems() as $item) {
                $data['items'][] = [
                    'description' => $item->getName(),
                    'quantity' => $item->getQtyToShip() ?: 1,
                    'item_id' => $item->getSku(),
                    'price_cents' => $item->getPrice() * 100
                ];
            }
            return $data;
        }else{
            return [];
        }
    }

    public function generate($data){
        $data['apiKey'] = $this->helper()->getApiKey();

        $url = $this->helper()->getApiUrl() . 'transaction/create';

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result);

        if($result->create_request->result == 'reject'){
            $this->log('Problema ao gerar boleto #'. $data['order_id'] .': ' . $result->create_request->response_message);
            return ['success' => false, 'message' => $result->create_request->response_message];
        }else if($result->create_request->result == 'success'){
            $order = Mage::getModel('sales/order')->loadByIncrementId($data['order_id']);

            $additional = [
                'boleto_url' => $result->create_request->bank_slip->url_slip_pdf,
                'linha_digitavel' => $result->create_request->bank_slip->digitable_line,
                'vencimento' => $result->create_request->due_date
            ];
            $order->getPayment()->setAdditionalInformation($additional)->save();

            return ['success' => true, 'additional' => $additional];
        }
    }

    public function addInformation($order, $additional){
        if($order && $order->getId() && is_array($additional) && count($additional) >= 1){
            foreach ($additional as $key => $value) {
                $order->getPayment()->setAdditionalInformation($key, $value)->save();
            }
        }
    }

    protected function helper(){
        return Mage::helper('foxsea_paghiper');
    }

    private function log($msg){
        Mage::log($msg, null, 'paghiper.log');
    }

}
