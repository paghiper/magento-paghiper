<?php 
/**
* Project: Modulo de Boleto Bancario - PagHiper 
*
* @author Alphacode - IT Solutions <www.alphacode.com.br>
*
*/

class Alphacode_Paghiper_PaymentController extends Mage_Core_Controller_Front_Action 
{
  public function gatewayAction() {
    if ($this->getRequest()->get("orderId"))
    {
      $arr_querystring = array(
        'flag' => 1, 
        'orderId' => $this->getRequest()->get("orderId")
      );
       
      Mage_Core_Controller_Varien_Action::_redirect('paghiper/payment/response', array('_secure' => false, '_query'=> $arr_querystring));
    }
  }
   
  public function redirectAction() {
    if (isset($_SESSION['paghiper']['data'])){    
        $response['bankSlipInfo'] = $_SESSION['paghiper']['data'];
    } else {
      /* GERA BOLETO - CHECKOUT CHECKOUT TRANSPARENTE */
      $response['bankSlipInfo'] = $this->generateBankSlip();

      if (!isset($_SESSION['paghiper']['data'])){
          $_SESSION['paghiper']['data'] = $response['bankSlipInfo']; 
      }
    }

    $this->loadLayout();
    $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','paghiper',array('template' => 'paghiper/redirect.phtml', 'response' => $response));

    $this->getLayout()->getBlock('content')->append($block);

    $this->renderLayout();
  }
 
  public function responseAction() {
    if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId")) 
    {
      $orderId = $this->getRequest()->get("orderId");
      $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
      $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
      $order->save();
       
      Mage::getSingleton('checkout/session')->unsQuoteId();
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=> false));
    }
    else
    {
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure'=> false));
    }
  }

  /**
  * Função responsavel por receber o retorno do Paghiper e atualizar o status de 1 pedido
  **/
  public function callbackAction() {
      //TOKEN gerado no painel do PAGHIPER = TOKEN SECRETO
      $token = Mage::getStoreConfig('payment/paghiper/trans_key');
      //Trata os dados do Post Recebido do Paghiper
      $idTransacao = $_POST['idTransacao'];
      $dataTransacao = $_POST['dataTransacao'];
      $dataCredito = $_POST['dataCredito'];
      $valorOriginal = $_POST['valorOriginal'];
      $valorLoja = $_POST['valorLoja'];
      $valorTotal = $_POST['valorTotal'];
      $numeroParcelas = $_POST['numeroParcelas'];
      $status = $_POST['status'];
      $nomeCliente = $_POST['nomeCliente'];
      $emailCliente = $_POST['emailCliente'];
      $rgCliente = $_POST['rgCliente'];
      $cpfCliente = $_POST['cpfCliente'];
      $sexoCliente =$_POST['sexoCliente'];
      $enderecoCliente = $_POST['enderecoCliente'];
      $complementoCliente = $_POST['complementoCliente'];
      $bairroCliente = $_POST['bairroCliente'];
      $cidadeCliente = $_POST['cidadeCliente'];
      $estadoCliente = $_POST['estadoCliente'];
      $cepCliente = $_POST['cepCliente'];
      $frete = $_POST['frete'];
      $tipoFrete = $_POST['tipoFrete'];
      $vendedorEmail = $_POST['vendedorEmail'];
      $numItem = $_POST['numItem'];
      $idPlataforma = $_POST['idPlataforma'];
      $codRetorno = $_POST['codRetorno'];
      $tipoPagamento = $_POST['tipoPagamento'];
      $codPagamento = $_POST['codPagamento'];
      $urlPagamento = $_POST['urlPagamento'];
      $linhaDigitavel = $_POST['linhaDigitavel'];
      $tipoTransacao = $_POST['tipoTransacao'];
      $idPartners = $_POST['idPartners'];
      //For para receber os produtos
      for ($x=1; $x <= $numItem; $x++) {
      $produto_codigo = $_POST['produto_codigo_'.$x];
      $produto_descricao = $_POST['produto_descricao_'.$x];
      $produto_qtde = $_POST['produto_qtde_'.$x];
      $produto_valor = $_POST['produto_valor_'.$x];
      /* Após obter as variáveis dos produtos, grava no banco de dados.
      Se produto já existe, atualiza os dados, senão cria novo pedido. */
      }
      //PREPARA O POST A SER ENVIADO AO PAGHIPER PARA CONFIRMAR O RETORNO
      //INICIO - NAO ALTERAR//
      $post = "idTransacao=$idTransacao" .
      "&status=$status" .
      "&codRetorno=$codRetorno" .
      "&valorOriginal=$valorOriginal" .
      "&valorLoja=$valorLoja" .
      "&token=$token";
      $enderecoPost = "https://www.paghiper.com/checkout/confirm/";
      ob_start();
      $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $enderecoPost);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_HEADER, false);
       curl_setopt($ch, CURLOPT_TIMEOUT, 30);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $resposta = curl_exec($ch);
       curl_close($ch);
       $confirmado = (strcmp ($resposta, "VERIFICADO") == 0);
       //FIM - NAO ALTERAR//
       if ($confirmado) {
          //Trata os dados do Post Recebido do Paghiper 
          $status = $_POST['status'];
          $idPlataforma = $_POST['idPlataforma'];

          switch ($status) {
            case 'Aprovado':
                $order = Mage::getModel('sales/order')->load($idPlataforma, 'increment_id');
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true);
                $history = $order->addStatusHistoryComment('Pedido aprovado através da Paghiper.', false);
                $history->setIsCustomerNotified(false);
                $order->save();
              break;

            // case 'Completo':
			// ### Ative apenas se necessario
            //     $order = Mage::getModel('sales/order')->load($idPlataforma, 'increment_id');
            //     $order->setData('state', "complete");
            //     $order->setStatus("complete");       
            //     $history = $order->addStatusHistoryComment('Pedido concluído através da Paghiper.', false);
            //     $history->setIsCustomerNotified(false);
            //     $order->save();
             //  break;

            case 'Cancelado':
                $order = Mage::getModel('sales/order')->load($idPlataforma, 'increment_id');
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true);
                $order->save();
              break;

            case 'Disputa':
                $order = Mage::getModel('sales/order')->load($idPlataforma, 'increment_id');
                $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true);
                $order->save();
              break;
          }
      } else {
        //SE O POST FOR NEGADO, ESSA AREA SERA HABILITADA
        echo '<br>negativo</br>';
      }
  }
    /* GERA BOLETO */
    public function generateBankSlip() {
        /******** INTERA DADOS **********/
        $order = new Mage_Sales_Model_Order();
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order->loadByIncrementId($orderId);

        $orderItems = $order->getItemsCollection()
                ->addAttributeToSelect('*')
                ->load();

        $paymentId = $order->getPayment()->getId();
        $payment_info = Mage::getModel('sales/order_payment')->load($paymentId);

        $shippingId = $order->getShippingAddress()->getId();
        $address = Mage::getModel('sales/order_address')->load($shippingId);

        $discount = Mage::getStoreConfig('payment/paghiper/discount');

        $maturity_date =  Mage::getStoreConfig('payment/paghiper/maturity_date');

        $subtotal = $order->base_subtotal;
        if (Mage::getStoreConfig('payment/paghiper/discount_group') && abs($order->base_discount_amount) > 0){
            $discount_total = (($subtotal / 100) * abs($discount)) + abs($order->base_discount_amount);
        } elseif ((($subtotal / 100) * abs($discount)) > abs($order->base_discount_amount)){
            $discount_total = ($subtotal / 100) * abs($discount);
        } elseif (abs($order->base_discount_amount) > 0) {
            $discount_total = abs($order->base_discount_amount);
        } else {
            $discount_total = 0;
        }

        $x = 1;
        $products = array();
        foreach($orderItems as $sItem){
            $products['produto_codigo_'.$x] = $sItem->getId();
            $products['produto_valor_'.$x] = $sItem->getPrice();
            $products['produto_descricao_'.$x] = $sItem->getName();
            $products['produto_qtde_'.$x] = $sItem->getQty_ordered();
            $x++; 
        }

        $params = array(
          'id_plataforma' => $orderId,
          'email_loja' => Mage::getStoreConfig('payment/paghiper/merchant_email'),
          'urlRetorno' => $this->getUrlReturn() . '/paghiper/payment/callback/',
          'vencimentoBoleto' => $maturity_date,
          'email' => $address->email,
          'nome' => $address->firstname . ' ' . $address->lastname,
          'cpf' => $payment_info->cpf_cnpj_paghiper,
          'telefone' => $address->telephone,
          'endereco' => $address->street,
          'cidade' => $address->city,
          'cep' => $address->postcode,
          'descontoBoleto' => $discount_total,
          'frete' => $payment_info->shipping_amount,
          'tipo_frete' => 'Frete',
          'pagamento' => '1',
		  'idPartners' => 'KAPK109D',
          'api' => 'json'
        );

        $params = array_merge($params, $products);

        return $this->httpPost("https://www.paghiper.com/checkout/",$params);
    }

    private function httpPost($url,$params){
        $postData = '';
        //create name value pairs seperated
        foreach($params as $k => $v) { $postData .= $k . '='.$v.'&'; }
        $postData = rtrim($postData, '&');
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output=curl_exec($ch);
        curl_close($ch);

        $data = json_decode($output);
        
        $bankSlipInfo['urlPagamento']   = $data->transacao[0]->urlPagamento;
        $bankSlipInfo['linhaDigitavel'] = $data->transacao[0]->linhaDigitavel;
        $bankSlipInfo['idPlataforma']   = $data->transacao[0]->idPlataforma;
        $bankSlipInfo['valorTotal']     = $this->reais($data->transacao[0]->valorTotal);

        return $bankSlipInfo;
    }
    private function reais($value) {
      return 'R$ '.number_format($value,2,',','.');
    }

    public function getUrlReturn(){
      $protocols = array(0 => 'https://www.', 1 => 'https://', 2 => 'http://www.', 3 => 'http://', 4 => 'www.', 5 => '');

      $url_base = str_replace($protocols, '', Mage::getBaseUrl());

      foreach ($protocols as $key => $protocol) {
        $url = $protocol . $url_base;

        if ($this->verifyURL($url)){
          return $url;
        } else {
          unset($url);
        }
      }

      return $url_base;
    }

    private function verifyURL($url){
      $handle = curl_init($url);
      curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);

      curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

      /* Get the HTML or whatever is linked in $url. */
      $response = curl_exec($handle);

      /* Check for 404 (file not found). */
      $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
      $output = curl_exec($handle);

      if($httpCode == 200) {
          return true;
      } else {
        return false;
      }
    }
}