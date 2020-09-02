<?php
    
    namespace glx\DB\Query\I;
    
    
    interface ConditionExpression
    {
        public function or($name, $operator = null, $value = null): Sequence;
        
        public function and($name, $operator = null, $value = null): Sequence;
    }
 
