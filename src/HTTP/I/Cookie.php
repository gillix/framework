<?php
    
    namespace glx\HTTP\I;
    
    
    interface Cookie
    {
        public function delete($name, array $options = []): void;
        
        public function has(string $name): bool;
        
        public function get(string $name);
        
        public function set($name, $value, $lifetime = null, string|null $path = null, string|null $domain = null, bool|null $secure = null, bool|null $httponly = null, string|null $samesite = null): void;
        
        public function apply(): void;
    }
