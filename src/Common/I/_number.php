<?php
    
    namespace glx\Common\I;
    
    interface _number
    {
        public function format($format): _string;
        
        public function abs();
    }
 
