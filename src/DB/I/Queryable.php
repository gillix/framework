<?php
    
    namespace glx\DB\I;
    
    use glx\DB\Query;

    interface Queryable
    {
        public function table($table, $alias = null): Query\I\Table;
        
        public function from($table, $alias = null): Query\I\SearchableTable;
        
        public function update(string $table = null, $where = null, array $fields = null): Query\I\Update;
        
        public function select(...$columns): Query\I\Select;
        
        public function insert(string $into = null, $fields = null): Query\I\Insert;
        
        public function delete(string $from = null, $where = null): Query\I\Delete;
    }