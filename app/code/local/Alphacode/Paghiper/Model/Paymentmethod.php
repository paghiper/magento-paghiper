<?php 

/**
* Project: Modulo de Boleto Bancario - PagHiper 
*
* @author Alphacode - IT Solutions <www.alphacode.com.br>
*
*/
class Alphacode_Paghiper_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract {
  protected $_code  = 'paghiper';
  protected $_formBlockType = 'paghiper/form_paghiper';
  protected $_infoBlockType = 'paghiper/info_paghiper';
 
  public function assignData($data){
    $info = $this->getInfoInstance();

    if ($data->getCpfCnpjPaghiper())
    {
      $info->setCpfCnpjPaghiper($data->getCpfCnpjPaghiper());
    }
 
    return $this;
  }
 
  public function validate(){
    parent::validate();
    $info = $this->getInfoInstance();

    $itens = Mage::getModel('checkout/cart')->getQuote()->getAllItems();

    $total = 0;

    $cart = Mage::getModel('checkout/cart')->getQuote();
    foreach ($cart->getAllItems() as $item) {
        $total = $total + ($item->getProduct()->getPrice() * $item->getQty());
    }

    if ($total < Mage::getStoreConfig('payment/paghiper/minimum_value')){
      $errorCode = 'invalid_data';
      $errorMsg = $this->_getHelper()->__("Valor mínimmo para pagamento boleto: " . $this->reais(Mage::getStoreConfig('payment/paghiper/minimum_value'))) . ".\n";
    }
       
    if (!$info->getCpfCnpjPaghiper()){
      $errorCode = 'invalid_data';
      $errorMsg = $this->_getHelper()->__("CPF/CNPJ é obrigatório.\n");
    }

    if (strlen($info->getCpfCnpjPaghiper()) > 11){
      if (!$this->cnpj($info->getCpfCnpjPaghiper())){
        $errorCode = 'invalid_data';
        $errorMsg = $this->_getHelper()->__("CPF ou CNPJ inválido!\n");
      }
    } else {
      if (!$this->cpf($info->getCpfCnpjPaghiper())){
        $errorCode = 'invalid_data';
        $errorMsg = $this->_getHelper()->__("CPF ou CNPJ inválido!\n");
      }
    }
 
    if ($errorMsg) 
    {
      Mage::throwException($errorMsg);
    }
 
    return $this;
  }
 
  public function getOrderPlaceRedirectUrl(){
    //busca dados do pedido, e faz a chamada ao paghiper e redireciona.
    return Mage::getUrl('paghiper/payment/redirect', array('_secure' => false));
  }

  private function reais($value) {
    return 'R$ '.number_format($value,2,',','.');
  }

  public function cpf($cpf = false){
        if ( ! function_exists('calc_digitos_posicoes') ) {
            function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
                // Faz a soma dos dígitos com a posição
                // Ex. para 10 posições: 
                //   0    2    5    4    6    2    8    8   4
                // x10   x9   x8   x7   x6   x5   x4   x3  x2
                //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                    $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
                    $posicoes--;
                }
         
                // Captura o resto da divisão entre $soma_digitos dividido por 11
                // Ex.: 196 % 11 = 9
                $soma_digitos = $soma_digitos % 11;
         
                // Verifica se $soma_digitos é menor que 2
                if ( $soma_digitos < 2 ) {
                    // $soma_digitos agora será zero
                    $soma_digitos = 0;
                } else {
                    // Se for maior que 2, o resultado é 11 menos $soma_digitos
                    // Ex.: 11 - 9 = 2
                    // Nosso dígito procurado é 2
                    $soma_digitos = 11 - $soma_digitos;
                }
         
                // Concatena mais um dígito aos primeiro nove dígitos
                // Ex.: 025462884 + 2 = 0254628842
                $cpf = $digitos . $soma_digitos;
                
                // Retorna
                return $cpf;
            }
        }
        
        // Verifica se o CPF foi enviado
        if ( ! $cpf ) {
            return false;
        }
     
        // Remove tudo que não é número do CPF
        // Ex.: 025.462.884-23 = 02546288423
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
        // Verifica se o CPF tem 11 caracteres
        // Ex.: 02546288423 = 11 números
        if ( strlen( $cpf ) != 11 ) {
            return false;
        }   
     
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($cpf, 0, 9);
        
        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = calc_digitos_posicoes( $digitos );
        
        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = calc_digitos_posicoes( $novo_cpf, 11 );
        
        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ( $novo_cpf === $cpf ) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    public function cnpj($cnpj = false){
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

}