## About Laravel

Instalação:

1- Verificando instalação
    
    Certifique-se de que uma versão compatível do php esteja instalada e adicionada ao PATH, juntamente com o composer (é possível verificar usando o comando php -v e composer --version).

2- Clonando o repositório

    No terminal, navegue até a pasta desejada e use o seguinte comando para clonar o repositório:

    git clone https://github.com/lmtsufape/vida-agroecologica-ecommerce-api.git

3- Instalando as dependências

    Na pasta do projeto, use o comando "composer install" para instalar todas as dependências necessárias. Talvez seja necessário ir até o arquivo php.ini na pasta raiz da instalação do php e descomentar algumas linhas para que as extensões sejam utilizadas corretamente (somente em caso de erro de extensão).

4- Copiando o .env.example

    Duplique o arquivo ".env.example" e renomeie-o para ".env".

5- Gerando chave do app

    Use o comando "php artisan key:generate" para gerar a chave do app.

6- Configurando .env

    Adicione a configuração do banco de dados (já está parcialmente configurado para o uso do postgresSQL) e do servidor de email ao ".env".
    Obs: O site mailtrap.io pode ser utilizado para configurar o servidor de email.

7- Rodando migrations e seeders

    Use o comando "php artisan migrate" para rodar as migrações e em seguida, use comando "php artisan db:seed" para rodar os seeders.

8- Rodando o servidor

    Execute o comando "php artisan serve" para rodar o servidor.

9- Rodando a queue

    Em uma nova aba do terminal, use o comando "php artisan queue:work" e deixe-o rodando para que ele possa realizar os jobs da aplicação.
