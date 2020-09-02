<?php
    
    namespace glx\HTTP\I;
    
    
    interface URI
    {
        public function port(string $value = null): string;
        
        public function scheme(string $value = null): string;
        
        public function host(string $value = null): string;
        
        public function path(string $value = null): string;
        
        public function query($value = null): Query;
        
        public function fragment(string $value = null): string;
        
        public function user(string $value = null): string;
        
        public function pass(string $value = null): string;
        
        public function get(string $name);
        
        public function parts(array $value = null): array;
        
        public function has(string $name): bool;
        
        public function with(array $params = []): self;
        
        public function __toString();
    }