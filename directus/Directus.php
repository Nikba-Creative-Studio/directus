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

    #Class Constructor
    public function __construct($config) {
        $this->accessToken = $config['token'];
        $this->accessUrl = $config['base'];

        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken));
    }

    #Error Log Function
    public function error_log($errorCode, $errorMessage) {
        die("Error: {$errorCode} - {$errorMessage}");
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
        $this->curl->get($this->accessUrl.self::ITEMS_ENDPOINT.$tableName.$id, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response->data;
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
        echo $this->accessUrl.self::HASH_ENDPOINT;
        $this->curl->post($this->accessUrl.self::HASH_ENDPOINT, $string);
        
        if ($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
        }
        else {
            return $this->curl->response;
        }
    }

}