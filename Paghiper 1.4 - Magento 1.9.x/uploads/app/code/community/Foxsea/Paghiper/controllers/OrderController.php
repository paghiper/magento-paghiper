<?php

class Foxsea_Paghiper_OrderController extends Mage_Core_Controller_Front_Action
{

    public function updateAction(){
        $post = file_get_contents("php://input");
        $post = preg_replace( "/\r|\n/", "", $post );
        parse_str($post, $data);

        $data['token'] = $this->helper()->getToken();

        $url = $this->helper()->getApiUrl() . 'transaction/notification';
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
        if($result->status_request->result == 'reject'){
            $this->log('Problema ao atualizar transação '. $data['transaction_id'] .': ' . $result->status_request->response_message);
        }else if($result->status_request->result == 'success'){
            $order_id = $result->status_request->order_id;
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            $status = $result->status_request->status;

            if(($status == 'paid' || $status == 'completed') && $order->getInvoiceCollection()->count() <= 0){
                // se o pedido for pago, geramos a fatura
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING)->setStatus(Mage_Sales_Model_Order::STATE_PROCESSING);
                $history = $order->addStatusHistoryComment('Pedido aprovado através da Paghiper.', false);
                $history->setIsCustomerNotified(true);
                $order->save();

                if($order->canInvoice()){
                    $items = [];
                    foreach ($order->getAllItems() as $item) {
                        $items[$item->getId()] = $item->getQtyOrdered();
                    }
                    Mage::getModel('sales/order_invoice_api')->create($order->getIncrementId(), $items, null, false, true);
                }
            }else if($status == 'refunded'){
                // se o pedido foi reembolsado, criamos um reembolso no magento
                $service = Mage::getModel('sales/service_order', $order);
                foreach ($order->getInvoiceCollection() as $invoice) {
                    if ($invoice->canRefund()) {
                        $creditmemo = $service->prepareInvoiceCreditmemo($invoice);
                        $creditmemo->register();
                        $creditmemo->save();
                    }
                }
                $order->save();
            }else if($status == 'canceled'){
                
               if($order['status'] == 'pending'){
                   
                   if($order->canCancel()) {
                    $order->cancel()->save();
                }	
                   
               }
            }
        }
    }

    protected function helper(){
        return Mage::helper('foxsea_paghiper');
    }

    private function log($msg){
        Mage::log($msg, null, 'paghiper_notifications.log');
    }

}
