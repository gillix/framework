<?php
    
    namespace glx\DB\Query;
    
    
    class QueryClause implements I\QueryClause
    {
        protected $target;
        
        public function __construct($target)
        {
            $this->target = $target;
        }
        
        public function target()
        {
            return $this->target;
        }
    }
