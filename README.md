# Módulo Magento 1.9.X - PAGHIPER

Módulo Oficial do PagHiper para Magento Permite a emissão de boletos e integração do gateway da Paghiper ao Magento. Este módulo implementa emissão de boletos registrados com retorno automático.

* ** Versão mais Recente:** 1.4
* ** Requisitos:** PHP >= 5.2.0, cURL ativado.
* ** Compatibilidade:** Qualquer versão Magento 1.9.X
 

Download do Módulo
[Baixar módulo](https://atendimento.paghiper.com/hc/article_attachments/360047393953/Paghiper_1.4_-_Magento_1.9.x.zip)

# Instalação

Após download dos arquivos do Módulo, descompacte o arquivo Paghiper_1.4_-_Magento_1.9.x.zip e mova os arquivos(que estão dentro da pasta Uploads) para pasta raiz da sua instalação do Magento.

 

 
![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002028633/magento5.jpg)
Figura 1


Após ter feito o upload dos arquivos acesse o painel administrativo de sua loja Magento, após ter feito login, clique no Menu Sistema, e vá até o Submenu Gerenciar Cache.


![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002030254/magento6.jpg)
Figura 2


Na pagina que carregou, 1º Clique em Marcar Todos, 2º verifique se realmente todos os Check box ficaram marcados, 3º escolha a opção 'atualizar' e clique em Enviar. (esta ação serve para garantir que não fique armazenado nenhuma informação desatualizada no cache, e assim o modulo perfeitamente).

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002030274/magento7.jpg)
Figura 3



# Configurando o Modulo

clique no Menu Sistema, e vá até o Configuração.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002029053/magento8.jpg)
Figura 4

Na pagina que irá carregar, vá até o menu lateral "Vendas" e clique Em “formas de pagamento”.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002028653/magento9.jpg)
Figura 5

Você Irá encontrar uma lista com algumas opções de pagamento, conforme ilustrado na imagem abaixo, dentre elas a opção PagHiper, onde você fará a configuração e ativação do Módulo.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360004430513/configmodulo.jpg)
Figura 6


 
* a) Status do módulo (Para ativar deixe como ativo).
* b) Descrição, que irá aparecer na hora do pagamento, aqui você pode inserir instruções para seu cliente.
* c) Título do módulo (será apresentado no checkout).
* d) ApiKey gerado pela PagHiper. Pode ser obtido em https://www.paghiper.com/painel/credenciais/
* e) Token gerado pela PagHiper.Pode ser obtido em https://www.paghiper.com/painel/credenciais/
* f) Número de dias para o vencimento do boleto
* g) Numero máximo de dias que o boleto fica em aberto após o vencimento.
Se não deseja que seu cliente pague após o vencimento, deixe o campo em branco.
* h) Percentual de multa, para boleto vencido. 0 á 2 %
* i) Juros para boleto vencido, se ativado, será cobrado uma taxa de 1% ao mês de atraso.
* j) Ativar desconto de antecipação.
* k) Porcentagem de desconto de antecipação.
* l) dias que o pagamento pode ser antecipado, deve ser menor que o vencimento
Por exemplo. se você colocar 5 dias para vencimento, se inserir 3 neste campo, significa, que até 3 dias antes do vencimento o cliente tem desconto por pagar antecipado.



Por Fim Clique em "Salvar"

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360024078734/magenovo.jpg)
Figura 7

 

Após instalar e configurar o Módulo, a forma de pagamento PagHiper deve aparecer como opção de pagamento no checkout da sua loja, conforme imagem abaixo:

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002031034/magento12.jpg)
Figura 8

Pronto! Agora você tem o módulo de boleto PagHiper instalado e configurado na sua loja Magento. Retorno automático OBS: Para atualização dos status dos pedidos na loja baseado no retorno da PagHiper é necessário que os seguintes status estejam disponíveis:

Processando - Código: processing

Completo - Código: complete ( esse status foi desabilitado afim de facilitar a conciliação do lojista.)

Cancelado - Código: canceled

Disputa - Código: payment_review



# Dúvidas sobre sua conta PagHiper?

Acesse nossa central de atendimento https://www.paghiper.com/atendimento

# Dúvidas sobre o Módulo?

Utilize o GitHub https://github.com/paghiper/magento-paghiper/issues

Boas vendas :D


