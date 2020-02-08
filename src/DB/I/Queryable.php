<?php
 
 namespace glx\DB\I;
 
 use glx\DB\Query;
 
 interface Queryable
 {
    public function table($table, $alias = NULL): Query\I\Table;
    public function from($table, $alias = NULL): Query\I\SearchableTable;
    public function update(string $table = NULL, $where = NULL, array $fields = NULL): Query\I\Update;
    public function select(...$columns): Query\I\Select;
    public function insert(string $into = NULL, $fields = NULL): Query\I\Insert;
    public function delete(string $from = NULL, $where = NULL): Query\I\Delete;
 }