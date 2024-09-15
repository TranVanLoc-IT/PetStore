<?php

namespace contract\repository;

interface ICypherQueryBuilder{
    //find
    public function match();

    // sort
    public function sort();

    // filter
    public function limit();
    public function where();

    // create
    public function create();
    public function merge();
    public function onCreate();
    public function onUpdate();


    // update
    // delete
    public function delete();
    public function detachDelete();

    // relationship

    // transaction

    // trigger

    // return
    public function return();
    // query
    public function getQuery();
}