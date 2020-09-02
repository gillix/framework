<?php
    
    namespace glx\HTTP\Server\I;
    
    use glx\HTTP;

    interface Request extends HTTP\I\Request
    {
        public function get(string $name = null);
        
        public function post(string $name = null);
        
        public function cookie(string $name = null);
        
        public function server(string $name = null);
        
        public function input(string $name = null);
        
        public function client(): Client;
        
        public function secure(): bool;
    }