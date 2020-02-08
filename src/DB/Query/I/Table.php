<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Table
 {
    public function insert($fields): int;
 }