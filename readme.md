
# Pré-requisitos

 - [PHP 5.6 >=](http://php.net/downloads.php);
 - [Composer](https://getcomposer.org/download/)

  # Passo a Passo para Execução do Projeto

	

 1. Clone ou faça download do projeto:`git clone https://github.com/erodrigues-developer/crawler-seminovosbh-api`;
 2. Dentro da pasta do projeto execute o comando: `composer install` e aguarde;
 3. Após baixada todas as depêncidencias basta executar o servidor interno do php na raiz do projeto: `php -S localhost:8080 -t public`

  

# Projeto Original
Este projeto foi adaptado à partir do projeto abaixo.
https://github.com/josefcts/crawler-seminovosbh-api

Principais modificações:

 - Criado classe de tratamento de exceção para retorno de erros no formato próprio para API (JSON);
 - Criado funcionalidade para buscar todas as marcas diretamente da página inicial;
 - Adicionada a propriedade imagem ao componente de Busca para recuperar imagens dos anúncios;