<?php

namespace repository\cypher;
use contract\repository\ICypherQueryBuilder;

class CypherQueryBuilder implements ICypherQueryBuilder{
    protected $query;
    protected $params = [];

    public function select($fields)
    {
        $this->query = "MATCH ($fields)";
        return $this;
    }

    public function where($condition, $params = [])
    {
        $this->query .= " WHERE $condition";
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function return($fields)
    {
        $this->query .= " RETURN $fields";
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getParams()
    {
        return $this->params;
    }
}