# Files API

## Server
![image](https://github.com/dykyi-roman/files-api/blob/master/server.png)

## Usage package
+ symfony
+ swagger
+ file stotage (mogilefs)

## Install Server
+ composer install
+ php bin/console doctrine:migrations:migrate
+ configurate DATABASE_URL and STORAGE_URL in .env file

## Install Client
+ composer install

##Usage
```php
$client = new FileClient(new Client(),new ResponseDataExtractor(), 'test','dev');

$id = $client->write(['name' => 'test_name', 'filePath' => '/path/fileName.doc']);
$client->get($id);
$client->delete($id);
```

## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)

