<?php
    
    namespace glx\HTTP\I;
    
    
    interface Cookie
    {
        public function delete($name, ?string $domain = null, ?string $path = null): void;
        
        public function has(string $name): bool;
        
        public function get(string $name);
        
        public function set($name, $value, $lifetime = null, string $path = null, string $domain = null, bool $secure = null, bool $httponly = null, string $samesite = null): void;
        
        public function apply(): void;
    }
