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
        'token' => 'AKCLyrU8Db4Av4DEQkHNrCl5',
        'base'  => 'https://cms.tophost.md/tophost/'
    ];
    
    
    #New Directus
    $api = new Nikba\Directus($config);
    
    /**
     * Get Items
     * 
    
    $headers = $api->getItems("headers");
    foreach($headers as $row) {
        echo $row->id;
    }
    */

    /**
     * Get Item
     *
    $header = $api->getItem("headers",1);
    print_r($header);
    */

    $header = $api->getFiles();
    print_r($header);

    


    
    