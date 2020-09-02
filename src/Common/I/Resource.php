<?php
    
    namespace glx\Common\I;
    
    interface Resource
    {
        public function type(): string;
        
        public function is(string $type, bool $not = false): bool;
        
        public function not(string $type): bool;
    }