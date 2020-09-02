<?php
    
    namespace glx\core\I;
    
    use glx\HTTP;

    interface File
    {
        public function source(): string;
        
        public function uri(): HTTP\I\URI;
        
        public function url(): string;
    }