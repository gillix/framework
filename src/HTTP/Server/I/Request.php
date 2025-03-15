<?php
    
    namespace glx\HTTP\Server\I;
    
    use glx\HTTP;

    interface Request extends HTTP\I\Request
    {
        public function get(string|null $name = null);
        
        public function post(string|null $name = null);
        
        public function cookie(string|null $name = null);
        
        public function server(string|null $name = null);
        
        public function input(string|null $name = null);
        
        public function client(): Client;
        
        public function secure(): bool;
    }
