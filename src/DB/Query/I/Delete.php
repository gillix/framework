<?php
 
 namespace glx\DB\Query\I;
 
 interface Delete extends Searchable, Writable
 {
    public function from($table, string $alias = NULL): self;
 }