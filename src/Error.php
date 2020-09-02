<?php
    
    namespace glx;
    
    class Error extends Exception
    {
        protected array $stack;
        protected array $context;
        
        public function __construct($message, array $context = null, \Throwable $previous = null)
        {
            $this->stack = Context::callstack()->array();
            if ($context) {
                $this->context = $context;
            }
            parent::__construct($message, 0, $previous);
        }
        
        public function stack(): array
        {
            return $this->stack;
        }
        
        public function context(): array
        {
            return $this->context ?? [];
        }
    }