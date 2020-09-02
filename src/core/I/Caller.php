<?php
    
    namespace glx\core\I;
    
    interface Caller
    {
        public function call($method, array $arguments = []);
    }