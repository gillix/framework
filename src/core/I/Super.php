<?php
    
    namespace glx\core\I;
    
    interface Super
    {
        public function get(string $name, $type = null): Joint|null;
    }
