<?php
 
 namespace glx\DB\Query\I;
 
 
 interface JoinClause
 {
    public function on($name, $operator = NULL, $value = NULL): WhereClause;
    public function using($field): Joinable;
 }
 
