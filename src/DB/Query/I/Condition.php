<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Condition extends ConditionExpression
 {
    public function name(): string;
    public function operator(): string;
    public function value();
 }
 
