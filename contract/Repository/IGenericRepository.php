<?php

namespace contract\repository;

/*
    @template T
*/
interface IGenericRepository{
    public function GetAllNode();
    public function GetAllNodeAndRelationship();
    public function GetOneNode();
    public function CheckNode();
    public function CreateNode();
    public function DeleteNode(T $node);
    public function UpdateNode();
    
    public function GetAllRelationshipOfNode();
    public function CheckRelationship();
    public function CreateRelationship();
    public function DeleteRelationship();
    public function UpdateRelationship();
}