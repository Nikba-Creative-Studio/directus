<?php
    #ERROR REPORTING (development, testing, production)
    define('ENVIRONMENT', 'development');
    if (defined('ENVIRONMENT')) {
        switch (ENVIRONMENT) {
            case 'development': 
                error_reporting(E_ALL); 
                @ini_set ( 'display_errors', true );
                break;	
            case 'testing':
            case 'production': 
                error_reporting(0); 
                break;
            default:
                exit('The application environment is not set correctly.');
        }
    }


    #Load Composer Vendors
    require 'vendor/autoload.php';

    #Load Directus
    $config = [
        'token' => 'API TOKEN',
        'base'  => 'https://nikba.com/project/'
    ];
    
    #New Directus
    $api = new Nikba\Directus($config);

    #Load Data
    $locales_data = $api->getItems("tableName");


    
    