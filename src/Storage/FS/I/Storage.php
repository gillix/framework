<?php
    
    namespace glx\Storage\FS\I;
    
    interface Storage extends \glx\Storage\I\Storage
    {
        public static function factory(array $types, $factory): void;
        
        public function structure(): Structure;
        
        public function compiler(): Compiler;
        
        public function produce(array $info, Structure $current): ?array;
        
        public function fetch(string $id): array;
        
        public function forget(string $id): void;
    }