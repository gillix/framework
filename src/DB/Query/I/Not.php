<?php
    
    namespace glx\DB\Query\I;
    
    
    interface Not extends ConditionExpression
    {
        public function expr(): ConditionExpression;
    }
 
