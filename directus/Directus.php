<?php
/*
=====================================================
 Copyright (c) 2020 NIKBA.COM
=====================================================
 File: Directus.php
=====================================================
*/
namespace Nikba;

class Directus extends BaseApi {

    /**
     * Fetch Items from a given table
     *
     * @param string $tableName
     * @param array $options
     *
     * @return Items Array
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
     * @return Item Array
     */
    public function getItem($tableName, $id, array $options = [])
    {
        $path = $this->buildPath(static::ITEM_ENDPOINT, [$tableName, $id]);
        $request = $this->performRequest('GET', $path, ['query' => $options]);
        return $request->data;
    }

    /**
     * Gets a list fo files
     *
     * @param array $params - Parameters
     *
     * @return EntryCollection
     */
    public function getFiles(array $options = [])
    {
        return $request->data;
    }

}