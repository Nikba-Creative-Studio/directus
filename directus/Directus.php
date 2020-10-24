<?php
/*
=====================================================
 Copyright (c) 2020 NIKBA.COM
=====================================================
 File: api.php
=====================================================
*/
namespace Nikba;

use Curl\Curl;

class Directus {

    #End Points
    const ITEMS_ENDPOINT    = 'items/';
    const FILES_ENDPOINT    = 'files/';
    const ASSETS_ENDPOINT   = 'assets/';
    const MAIL_ENDPOINT     = 'mail';
    const HASH_ENDPOINT     = 'utils/hash';
    const CHHASH_ENDPOINT   ='utils/hash/match';
    const STRING_ENDPOINT   = 'utils/random/string';

    #Class Constructor
    public function __construct($config) {
        $this->accessToken = $config['token'];
        $this->accessUrl = $config['base'];

        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken));
    }

    #Error Log Function
    public function error_log($errorCode, $errorMessage) {
        die("Error Code: {$errorCode} <br/> Error Message: {$errorMessage}");
    }
    
    #Get Items    
    public function getItems($tableName, array $options = [])
    {        
        $this->curl->get($this->accessUrl.self::ITEMS_ENDPOINT.$tableName, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
        }
    }
    
    #Get Item  
    public function getItem($tableName, $id, array $options = [])
    {        
        $this->curl->get($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
        }
    }

    #Create an Item
    public function postItem($tableName, array $data = [])
    {        
        $this->curl->post($this->accessUrl.self::ITEMS_ENDPOINT.$tableName, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
        }
    }

    #Update an Item
    public function updateItem($tableName, $id, array $data = [])
    {        
        $this->curl->patch($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
        }
    }

    #Delete an Item
    public function deleteItem($tableName, $id)
    {        
        $this->curl->delete($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response;
        }
    }
    
    #Get Files  
    public function getFiles(array $options = [])
    {        
        $this->curl->get($this->accessUrl.self::FILES_ENDPOINT, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
        }
    }
    
    #Get File 
    public function getFile($id)
    {        
        $this->curl->get($this->accessUrl.self::FILES_ENDPOINT.$id);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response;
        }
    }

    #Get Asset 
    public function getAssets($id, array $options = [])
    {        
        $this->curl->get($this->accessUrl.self::ASSETS_ENDPOINT.$id, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response;
        }
    }

    #Send Email
    public function sendEmail(array $options = [])
    {
        $this->curl->post($this->accessUrl.self::MAIL_ENDPOINT, $options);
        
        if ($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response;
        }
    }

    #Create a Hash
    public function getHash($string)
    {
        $data = [ 'string' => $string ];
        $this->curl->post($this->accessUrl.self::HASH_ENDPOINT, $data);
        
        if ($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data->hash;
        }
    }

    #Verify a Hashed String
    public function checkHash($string, $hash)
    {
        $data = [ 
            'string' => $string,
            'hash'   => $hash
        ];
        $this->curl->post($this->accessUrl.self::CHHASH_ENDPOINT, $data);
        
        if ($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data->valid;
        }
    }

    #Generate a Random String
    public function getString($lenght=10)
    {
        $data = [ 'length' => $lenght ];
        $this->curl->post($this->accessUrl.self::STRING_ENDPOINT, $data);
        
        if ($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data->random;
        }
    }

}