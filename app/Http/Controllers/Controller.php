<?php

namespace App\Http\Controllers;
use Symfony\Component\Yaml\Yaml;

abstract class Controller
{
    //
    protected $queryDatasource;
    protected $neo4j;

    /**
     * Cấu hình các thông tin lấy chuỗi query và lấy DI Service Neo4j cấu hình .env trong provider của app
     */
    public function __construct(){
        $this->queryDatasource = Yaml::parseFile(storage_path('/queries/queries.yaml'));
        $this->neo4j = app("neo4j");
    }
}
