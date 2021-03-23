<?php
/*
=====================================================
 Copyright (c) 2021 NIKBA.COM
=====================================================
 File: Directus.php
=====================================================
*/
namespace Nikba;

use Nikba\ArrayUtils;

class Directus extends BaseApi {

    /**
     * Fetch Items from a given table
     *
     * @param string $tableName
     * @param array $options
     *
     * @return array $data
     */
    public function getItems($tableName, array $options = [])
    {
        $path = $this->buildPath(static::ITEMS_ENDPOINT, $tableName);
        $request = $this->performRequest('GET', $path, ['query' => $options]);
        return $request->data;
    }

    /**
     * Get an entry in a given table by the given ID
     *
     * @param mixed $id
     * @param string $tableName
     * @param array $options
     *
     * @return array $data
     */
    public function getItem($tableName, $id, array $options = [])
    {
        $path = $this->buildPath(static::ITEM_ENDPOINT, [$tableName, $id]);
        $request = $this->performRequest('GET', $path, ['query' => $options]);
        return $request->data;
    }

    /**
     * Create a new item in the given table name
     *
     * @param $tableName
     * @param array $data
     *
     * @return array $data
     */
    public function createItem($tableName, array $data)
    {
        $path = $this->buildPath(static::ITEMS_ENDPOINT, $tableName);
        $data = $this->processData($tableName, $data);
        $request = $this->performRequest('POST', $path, ['body' => $data]);
        return $request->data;
    }

    /**
     * Update the item of the given table and id
     *
     * @param $tableName
     * @param $id
     * @param array $data
     *
     * @return array $data
     */
    public function updateItem($tableName, $id, array $data)
    {
        $path = $this->buildPath(static::ITEM_ENDPOINT, [$tableName, $id]);
        $data = $this->processData($tableName, $data);
        $request = $this->performRequest('PATCH', $path, ['body' => $data]);
        return $request->data;
    }

    /**
     * Deletes the given item id(s)
     *
     * @param string $tableName
     * @param $id
     *
     * @return status code
     */
    public function deleteItem($tableName, $id)
    {
        $path = $this->buildPath(static::ITEM_ENDPOINT, [$tableName, $id]);
        $request = $this->performRequest('DELETE', $path);
        return $request;
    }

    /**
     * Gets a list fo files
     *
     * @param array $options - Parameters
     *
     * @return array $data
     */
    public function getFiles(array $options = [])
    {
        $path = $this->buildPath(static::FILES_ENDPOINT, []);
        $request = $this->performRequest('GET', $path, ['query' => $options]);
        return $request->data;
    }

    /**
     * Gets the information of a given file ID
     *
     * @param $id
     *
     * @return array $data
     */
    public function getFile($id, array $options = [])
    {
        $path = $this->buildPath(static::FILE_ENDPOINT, [$id]);
        $request = $this->performRequest('GET', $path, ['query' => $options]);
        return $request->data;
    }

    /**
     * Gets the information of a given asset ID
     *
     * @param $id
     *
     * @return file
     */
    public function getAssets($id, array $options = [])
    {
        $path = $this->buildPath(static::ASSETS_ENDPOINT, [$id]);
        $request = $this->performRequest('GET', $path, ['query' => $options, 'response' => 'asset']);
        #@header("Content-Type: image/jpeg");
        return $request;
    }

    /**
     * Gets a hashed value from the given string
     *
     * @param string $string
     * @param array $options
     *
     * @return string $hash
     */
    public function getHash($string, array $options = [])
    {
        $path = $this->buildPath(static::HASH_ENDPOINT);

        $data = [
            'string' => $string
        ];

        if (ArrayUtils::has($options, 'hasher')) {
            $data['hasher'] = ArrayUtils::pull($options, 'hasher');
        }

        $data['options'] = $options;

        $request = $this->performRequest('POST', $path, ['body' => $data]);
        return $request->data->hash;
    }

    /**
     * Compare a hashed value with string
     *
     * @param string $string
     * @param array $hash
     *
     * @return 1 = true
     */
    public function checkHash($string, $hash)
    {
        $path = $this->buildPath(static::CHHASH_ENDPOINT);

        $data = [ 
            'string' => $string,
            'hash'   => $hash
        ];

        $request = $this->performRequest('POST', $path, ['body' => $data]);
        return $request->data->valid;
    }

    /**
     * Gets a random alphanumeric string
     *
     * @param array $options
     *
     * @return string $random
     */
    public function getRandom(array $options = [])
    {
        $path = $this->buildPath(static::STRING_ENDPOINT);

        $request = $this->performRequest('POST', $path, ['body' => $options]);
        return $request->data->random;
    }

    /**
     * Send an Email
     *
     * @param array $options
     *
     * $option = [
     *              "to" => "office@nikba.com",
     *              "subject"=> "test",
     *              "body" => "Hello <b>{{name}}</b>, this is your new password {{password}}.",
     *              "data" => [
     *                  "name" =>"John Doe",
     *                  "password" => "secret"
     *              ]
     *             ];
     * @return status code
     */
    public function sendEmail(array $options = [])
    {
        $path = $this->buildPath(static::MAIL_ENDPOINT);

        $request = $this->performRequest('POST', $path, ['body' => $options]);
        return $request;
    }

}