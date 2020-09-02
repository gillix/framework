<?php
    
    namespace glx\DB\Query\I;
    
    
    interface Joinable extends Searchable
    {
        public function join($table, $on = null, string $type = 'inner'): JoinClause;
        
        public function left($table, $on = null): JoinClause;
        
        public function right($table, $on = null): JoinClause;
        
        public function cross($table, $on = null): JoinClause;
    }