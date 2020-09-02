<?php
    
    namespace glx\Config\I;
    
    interface Reader
    {
        public function parse(string $content): array;
        
        public static function get(string $format = null): self;
        
        public static function default(string $format = null): string;
        
        public static function read(string $path): array;
    }
