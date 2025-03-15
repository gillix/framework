<?php
    
    namespace glx\core\I;
    
    interface Inheritor
    {
        public function inheritedFrom($ancestor): bool;
        
        public function super(string|null $ancestor = null): Super;
    }
