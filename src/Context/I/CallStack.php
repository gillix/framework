<?php
    
    namespace glx\Context\I;
    
    use glx\core;
    use SplStack;

    interface CallStack
    {
        public function enter(core\I\Joint $method);
        
        public function release();
        
        public function empty(): bool;
        
        public function current(): core\I\Joint;
        
        public function stack(): SplStack;
        
        public function array(): array;
    }