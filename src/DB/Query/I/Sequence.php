<?php
    
    namespace glx\DB\Query\I;
    
    
    interface Sequence extends ConditionExpression
    {
        public function add(ConditionExpression $entry, string $relation = 'and'): Sequence;
        
        public function entries(): array;
    }