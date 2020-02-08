<?php
 
 namespace glx\DB\Query\I;
 
 
 interface WhereClause
 {
    public function or($name, $operator = NULL, $value = NULL): self;
    public function and($name, $operator = NULL, $value = NULL): self;
 }
