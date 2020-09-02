<?php
    
    namespace glx\DB\Query;
    
    
    class JoinCondition implements I\JoinCondition
    {
        protected array $condition;
        
        public function init($condition, string $type = 'on')
        {
            $this->condition = ['type' => $type, 'condition' => $condition];
        }
        
        public function inited(): bool
        {
            return isset($this->condition);
        }
        
        public function condition(): array
        {
            return $this->condition ?? [];
        }
    }
 