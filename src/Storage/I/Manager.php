<?php
    
    namespace glx\Storage\I;
    
    interface Manager
    {
        public static function get($label, array|null $options = null): ?Storage;
        
        public static function register(string $label, $storage);
    }
