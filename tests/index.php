<?php 
    echo "Start Test";

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

    require_once '../vendor/autoload.php';
    
    $config = [
        'token' => '',
        'base'  => ''
    ];

    $api  =  new Nikba\Directus($config);

    $data  =  $api->getItems("locales");
    print_r($data);
?>