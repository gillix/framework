<?php
 
 namespace glx\DB\Query\I;
 
 
 interface ConditionExpression
 {
    public function or($name, $operator = NULL, $value = NULL): Sequence;
    public function and($name, $operator = NULL, $value = NULL): Sequence;
 }
 
