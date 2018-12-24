Português

Execute os seguintes passos:
   1 - Pelo terminal, entre no diretório do projeto e execute: 
     1.1 Composer install
     1.2 NPM install
     1.3 NPM run dev
     1.4 php artisan key: generate
   2- Renomear o arquivo .env.example para ".env"
   3- Definir os parametros de conexão com a base de dados no arquivo ".env"
   4- Novamente no terminal, execute
    4.1- php artisan migrate: fresh
    4.2- php artisan serve


ENGLISH 

Run the following steps:
   1- In the terminal enter in project directrory and execute:
      1.1 Composer install
      1.2 NPM install
      1.3 NPM run dev
      1.4 php artisan key: generate
   2- Rename the ".env.example" file to ".env"
   3- In the new ".env" file change the database conection parameters
   4- Now, in the terminal again,execute:
      4.1 php artisan migrate: fresh
      4.2 php artisan serve
