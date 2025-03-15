<?php
    
    namespace glx\HTTP\Server\I;
    
    use glx\HTTP;

    interface Response extends HTTP\I\Response
    {
        public function header($name, string|null $value = null): string;
        
        public function headers(array|null $headers = null): array;
        
        public function body(string|null $content = null): string;
        
        public function contentType(string|null $type = null): string;
        
        public function status(int|null $code = null): int;
        
        public function redirect($url): void;
        
        public function apply(): void;
    }
