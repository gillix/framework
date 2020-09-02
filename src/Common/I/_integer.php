<?php
    
    namespace glx\Common\I;
    
    interface _integer extends _number
    {
        public function float(): _float;
        
        public function get(): int;
    }
 
