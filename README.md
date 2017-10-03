# magento-paghiper
Módulo Oficial do PagHiper para Magento
Permite a emissão de boletos e integração do gateway da Paghiper ao Magento. Este módulo implementa emissão de boletos registrados com retorno automático.

* **Versão mais Recente:** 1.2
* **Requisitos:** PHP >= 5.2.0, cURL ativado.
* **Compatibilidade:** Qualquer versão Magento 1.9.X



# Instalação
Após download dos arquivos do Módulo, descompacte o arquivo paghiper_magento.zip e mova os arquivos para pasta app que está na raiz da sua instalação do Magento.
	
![alt tag](https://raw.githubusercontent.com/paghiper/magento-paghiper/master/tutorial/pastaapp.jpg)

Mova também, as imagem da pasta media para a pasta media do seu magento.

![alt tag](https://raw.githubusercontent.com/paghiper/magento-paghiper/master/tutorial/pastamedia.jpg)

# Configuração
Após a instalação do módulo, no admin da loja, vá até a página de Formas de Pagamento, acessando: Sistema > Configuração > Formas de Pagamento (Menu lateral “Vendas”) .
Em “formas de pagamento” você deve encontrar uma lista com algumas opções de pagamento, conforme ilustrado na imagem abaixo, dentre elas a opção PagHiper, onde você fará a configuração e ativação do Módulo.

![alt tag](https://raw.githubusercontent.com/paghiper/magento-paghiper/master/tutorial/configmodulo.jpg)

![alt tag](https://raw.githubusercontent.com/paghiper/magento-paghiper/master/tutorial/configmodulo2.jpg)


1. Seu e-mail de cadastro na PagHiper.
2. Chave de API gerada pela PagHiper.
3. Título do módulo (será apresentado no checkout).
4. Status do módulo (ativo ou inativo).
5. Valor mínimo para pagamento utilizando o módulo PagHiper (Ex. 10.5)
6. Número de dias para o vencimento do boleto
7. Desconto aplicado no boleto (%)
8. Status inicial de pedidos finalizados com a PagHiper como forma de pagamento.
9. Para quais países a forma de pagamento aparecerá como opção, todos ou apenas para um país específico.
10. Caso o módulo PagHiper seja selecionado para algum país específico, selecione-o nesta opção.


Após instalar e configurar o Módulo, a forma de pagamento PagHiper deve aparecer como opção de pagamento no checkout da sua loja, conforme imagem abaixo:

![alt tag](https://raw.githubusercontent.com/paghiper/magento-paghiper/master/tutorial/checkout.jpg)

Pronto! Agora você tem o módulo de boleto PagHiper instalado e configurado na sua loja Magento.
Retorno automático
OBS: Para atualização dos status dos pedidos na loja baseado no retorno da PagHiper é necessário que os seguintes status estejam disponíveis:

Processando - Código: processing

~~ Completo - Código: complete ~~  esse status foi desabilitado afim de facilitar a conciliação do lojista.


Cancelado - Código: canceled

Disputa - Código: payment_review

# Dúvidas sobre sua conta PagHiper?

Acesse nossa central de atendimento https://www.paghiper.com/atendimento

# Dúvidas sobre o Módulo?

Utilize o GitHub https://github.com/paghiper/magento-paghiper/issues

Boas vendas :D



