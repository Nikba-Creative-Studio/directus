
# Directus 8 PHP SDK

For PHP driven applications, use this SDK to more easily communicate with your Directus API.

## Requirements

  - PHP 7.2

## Install
### Via Composer

You can install the SDK using [Composer](https://getcomposer.org/) by adding nikba/directus to your composer.json require list.

     composer require nikba/directus 

Composer will download all dependencies and copy them into a directory with the name of `vendor`.

To use the SDK you have to include the [composer autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading). The composer autoload is a file that is located in the `vendor` directory, named `autoload.php`.

    require_once 'vendor/autoload.php';
  
## Usage
### Config Connection

    $config  = [
    	'token'  =>  'API TOKEN', //User API Token
    	'base'  =>  'https://nikba.com/project/' //Directus url
    ];
    
    $api  =  new Nikba\Directus($config);
### Getting the whole response

    $data  =  $api->getItems("tableName");

