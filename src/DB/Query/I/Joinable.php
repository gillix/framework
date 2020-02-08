<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Joinable extends Searchable
 {
    public function join($table, $on = NULL, string $type = 'inner'): JoinClause;
    public function left($table, $on = NULL): JoinClause;
    public function right($table, $on = NULL): JoinClause;
    public function cross($table, $on = NULL): JoinClause;
 }