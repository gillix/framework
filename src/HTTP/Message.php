<?php
    
    namespace glx\HTTP;
    
    class Message
    {
        protected array  $headers;
        protected string $body;
        protected string $version = '1.1';
        
        public function __construct(array $headers = [], $body = '', $version = '1.1')
        {
            $this->headers = $headers;
            $this->body = $body;
            $this->version = $version;
        }
        
        public function header($name): string
        {
            return $this->headers[$name] ?? '';
        }
        
        public function headers(): array
        {
            return $this->headers;
        }
        
        public function version(): string
        {
            return $this->version;
        }
        
        public function body(): string
        {
            return $this->body;
        }
    }