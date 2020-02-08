<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Searchable
 {
    public function where($name, $operator = NULL, $value = NULL): WhereClause;
    public function order($by, $direction = NULL): Searchable;
    public function limit(int $count, int $offset = NULL): Searchable;
    public function offset(int $offset): Searchable;
 }