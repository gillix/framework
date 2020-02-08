<?php
 
 namespace glx\DB\Query\I;
 
 interface Select extends Joinable, Query, Fetching
 {
    public function select(...$columns): self;
    public function having($name, $operator, $value): WhereClause;
    public function group(...$columns): self;
    public function from($table, string $alias = NULL): self;
 }

 