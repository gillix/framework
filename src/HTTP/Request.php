<?php
    
    namespace glx\HTTP;
    
    
    class Request extends Message implements I\Request
    {
        protected string $method;
        protected I\URI  $uri;
        
        public function __construct(array $params)
        {
            $this->uri = $params['uri'] instanceof I\URI ? $params['uri'] : new URI($params['uri'] ?? '');
            $this->method = strtolower($params['method'] ?? 'get');
            parent::__construct($params['headers'] ?? [], $params['body'] ?? '', $params['version'] ?? '1.1');
        }
        
        public function method(): string
        {
            return $this->method;
        }
        
        public function uri(): I\URI
        {
            return $this->uri;
        }
        
        public function target(): string
        {
            return $this->uri()->path();
        }
    }