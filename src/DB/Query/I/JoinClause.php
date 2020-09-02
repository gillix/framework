<?php
    
    namespace glx\DB\Query\I;
    
    
    interface JoinClause
    {
        public function on($name, $operator = null, $value = null): WhereClause;
        
        public function using($field): Joinable;
    }
 
