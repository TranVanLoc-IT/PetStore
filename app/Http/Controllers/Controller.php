<?php

namespace App\Http\Controllers;
use Symfony\Component\Yaml\Yaml;

abstract class Controller
{
    //
    protected $queryDatasource;
    protected $neo4j;

    public function __construct(){
        $this->queryDatasource = Yaml::parseFile(storage_path('/queries/queries.yaml'));
        $this->neo4j = app("neo4j");
    }
}
