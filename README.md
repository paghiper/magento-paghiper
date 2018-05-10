# magento-paghiper
Módulo Oficial do PagHiper para Magento
Permite a emissão de boletos e integração do gateway da Paghiper ao Magento. Este módulo implementa emissão de boletos registrados com retorno automático.

* **Versão mais Recente:** 1.3
* **Requisitos:** PHP >= 5.2.0, cURL ativado.
* **Compatibilidade:** Qualquer versão Magento 1.9.X



# Instalação
Após download dos arquivos do Módulo, descompacte o arquivo module_paghiper_v1_3_magento_1_9.zip e mova os arquivos(que estão dentro da pasta Uploads) para pasta raiz da sua instalação do Magento.
	
![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002028633/magento5.jpg)

Após ter feito o upload dos arquivos acesse o painel administrativo de sua loja Magento, após ter feito login, clique no Menu Sistema, e vá até o Submenu Gerenciar Cache.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002030254/magento6.jpg)

Na pagina que carregou, 1º Clique em Marcar Todos, 2º verifique se realmente todos os Check box ficaram marcados, 3º escolha a opção 'atualizar' e clique em Enviar. (esta ação serve para garantir que não fique armazenado nenhuma informação desatualizada no cache, e assim o modulo perfeitamente).

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002030274/magento7.jpg)




# Configuração
clique no Menu Sistema, e vá até o Configuração.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002029053/magento8.jpg)

Na pagina que irá carregar, vá até o menu lateral "Vendas" e clique Em “formas de pagamento” .

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002028653/magento9.jpg)

Você Irá encontrar uma lista com algumas opções de pagamento, conforme ilustrado na imagem abaixo, dentre elas a opção PagHiper, onde você fará a configuração e ativação do Módulo.

![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360004430513/configmodulo.jpg)

a) Seu e-mail de cadastro na PagHiper.
b) Token gerado pela PagHiper.Pode ser obtido em https://www.paghiper.com/painel/credenciais/
c) Título do módulo (será apresentado no checkout).
d) Status do módulo (Para ativar deixe como ativo).
e) Valor mínimo para pagamento utilizando o módulo PagHiper (Ex. 10.5)
f) Número de dias para o vencimento do boleto
g) Status inicial de pedidos finalizados com a PagHiper como forma de pagamento.
h) Aplicar o desconto de cupom junto com o desconto de boleto (ativando essa opção ele irá somar o desconto aplicado no boleto, juntamente com descontos de cupom disponibilizado na loja, deixando ela desativada, o desconto considerado será o de maior valor, exemplo o cliente utilize um cupom de 15% e no modulo esteja 10%, o desconto considerado sera apenas o do cupom 15%)
i) Desconto aplicado no boleto em porcentagem (%)
j) Para quais países a forma de pagamento aparecerá como opção, todos ou apenas para um país específico.
k) Caso o módulo PagHiper seja selecionado para algum país específico, selecione-o nesta opção.

Por Fim Clique em "Salvar"


![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360003768433/magento1.1.jpg)

Após instalar e configurar o Módulo, a forma de pagamento PagHiper deve aparecer como opção de pagamento no checkout da sua loja, conforme imagem abaixo:


![alt tag](https://atendimento.paghiper.com/hc/article_attachments/360002031034/magento12.jpg)

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



