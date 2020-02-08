<?php
 
 namespace glx\DB\Query\I;
 
 
 interface SearchableTable extends Searchable
 {
    public function delete(array $where = NULL): int;
 }
