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
    const ITEMS_ENDPOINT        = 'items/';
    const FILES_ENDPOINT        = 'files/';
    const ASSETS_ENDPOINT       = 'assets/';
    const COLLECTIONS_ENDPOINT  = 'collections';
    const FIELDS_ENDPOINT       = 'fields';
    const MAIL_ENDPOINT         = 'mail';
    const HASH_ENDPOINT         = 'utils/hash';
    const CHHASH_ENDPOINT       = 'utils/hash/match';
    const STRING_ENDPOINT       = 'utils/random/string';

    #Class Constructor
    public function __construct($config) {
        $this->accessToken = $config['token'];
        $this->accessUrl = $config['base'];       
    }

    #Init Curl
    public function connect() {
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken));
    }

    #Close Curl
    public function close() {
        $this->curl->close();
    }

    #Error Log Function
    public function error_log($errorCode, $errorMessage) {
        die("Error Code: {$errorCode} <br/> Error Message: {$errorMessage}");
    }
    
    /*  Items 
        Returns an array of item objects.
    */
    
    #Get Items    
    public function getItems($tableName, array $options = [])
    {        
        $this->connect();

        $this->curl->get($this->accessUrl.self::ITEMS_ENDPOINT.$tableName, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data;
            $this->close();
            return $data;
        }
    }
    
    #Get Item  
    public function getItem($tableName, $id, array $options = [])
    {        
        $this->connect();

        $this->curl->get($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data;
            $this->close();
            return $data;
        }
    }

    #Create an Item
    public function postItem($tableName, array $data = [])
    {       
        $this->connect();

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
        $this->connect();

        $this->curl->patch($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data;
            $this->close();
            return $data;
        }
    }

    #Delete an Item
    public function deleteItem($tableName, $id)
    {        
        $this->connect();

        $this->curl->delete($this->accessUrl.self::ITEMS_ENDPOINT.$tableName."/".$id);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response;
            $this->close();
            return $data;
        }
    }
    
    /*  Files 
        Returns an array of Files objects.
    */

    #Get Files  
    public function getFiles(array $options = [])
    {  
        $this->connect();

        $this->curl->get($this->accessUrl.self::FILES_ENDPOINT, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response;
            $this->close();
            return $data;
        }
    }
    
    #Get File 
    public function getFile($id)
    {    
        $this->connect();

        $this->curl->get($this->accessUrl.self::FILES_ENDPOINT.$id);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response;
            $this->close();
            return $data;
        }
    }

    #Get Asset 
    public function getAssets($id, array $options = [])
    {   
        $this->connect();

        $this->curl->get($this->accessUrl.self::ASSETS_ENDPOINT.$id, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response;
            $this->close();
            return $data;
        }
    }

    /*  Collections 
        Returns an array of Collections objects.
    */
    
    #Get Collection    
    public function getCollections($id=false)
    {        
        $this->connect();

        #Individual collection
        $collectionId = ($id!=false)? '/'.$id : false;

        $this->curl->get($this->accessUrl.self::COLLECTIONS_ENDPOINT.$collectionId);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data;
            $this->close();
            return $data;
        }
    }

    /*  Fields 
        Returns an array of Fields objects.
    */
    
    #Get Field    
    public function getFields($id=false)
    {        
        $this->connect();

        #Individual collection
        $fieldId = ($id!=false)? '/'.$id : false;

        $this->curl->get($this->accessUrl.self::FIELDS_ENDPOINT.$fieldId);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data;
            $this->close();
            return $data;
        }
    }

    /*  Utilities 
        Various utility you can use to simplify your development flow.
    */

    #Send Email
    public function sendEmail(array $options = [])
    {
        $this->connect();

        $this->curl->post($this->accessUrl.self::MAIL_ENDPOINT, $options);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response;
            $this->close();
            return $data;
        }
    }

    #Create a Hash
    public function getHash($string)
    {
        $this->connect();

        $data = [ 'string' => $string ];
        $this->curl->post($this->accessUrl.self::HASH_ENDPOINT, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data->hash;
            $this->close();
            return $data;
        }
    }

    #Verify a Hashed String
    public function checkHash($string, $hash)
    {
        $this->connect();

        $data = [ 
            'string' => $string,
            'hash'   => $hash
        ];
        $this->curl->post($this->accessUrl.self::CHHASH_ENDPOINT, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data->valid;
            $this->close();
            return $data;
        }
    }

    #Generate a Random String
    public function getString($lenght=10)
    {
        $this->connect();
        
        $data = [ 'length' => $lenght ];
        $this->curl->post($this->accessUrl.self::STRING_ENDPOINT, $data);
        
        if($this->curl->error) {
            $this->error_log($this->curl->errorCode, $this->curl->errorMessage);
            $this->close();
        }
        else {
            $data = $this->curl->response->data->random;
            $this->close();
            return $data;
        }
    }

}