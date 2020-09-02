<?php
    
    namespace glx\HTTP\Server\I;
    
    use glx\HTTP;

    interface Response extends HTTP\I\Response
    {
        public function header($name, string $value = null): string;
        
        public function headers(array $headers = null): array;
        
        public function body(string $content = null): string;
        
        public function contentType(string $type = null): string;
        
        public function status(int $code = null): int;
        
        public function redirect($url): void;
        
        public function apply(): void;
    }