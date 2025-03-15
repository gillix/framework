<?php
    
    namespace glx\HTTP\I;
    
    
    interface URI
    {
        public function port(string|null $value = null): string;
        
        public function scheme(string|null $value = null): string;
        
        public function host(string|null $value = null): string;
        
        public function path(string|null $value = null): string;
        
        public function query($value = null): Query;
        
        public function fragment(string|null $value = null): string;
        
        public function user(string|null $value = null): string;
        
        public function pass(string|null $value = null): string;
        
        public function get(string $name);
        
        public function parts(array|null $value = null): array;
        
        public function has(string $name): bool;
        
        public function with(array $params = []): self;
        
        public function __toString();
    }
