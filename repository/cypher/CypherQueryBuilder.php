<?php

namespace repository\cypher;
use contract\repository\ICypherQueryBuilder;

class CypherQueryBuilder implements ICypherQueryBuilder{
    protected $query;
    protected $param = [];
    //find
    public function match($criteria, $params = [])
    {
        $this->query .= "MATCH ( " . $criteria . ")";
        
    }

    // sort
    public function sort($field, $option){}

    // filter
    public function limit($sumber){}
    public function where($criteria){}

    // create
    public function create($data){}
    public function merge(){}
    public function onCreate(){}
    public function onUpdate(){}

    // update
    public function updateNode($command, $data = []){}
    public function updateRelationship($command, $data = []){}   

    // delete
    public function delete($criteria){}
    public function detachDelete(){}
    // aggregate
    public function sum(){}
    public function count(){}
    public function average(){}
    // relationship
    public function relationship($command, $data = []){}
    // return
    public function return($fields){}
    // query
    public function getQuery(){}
     // parameters
     public function getParams(){}
}