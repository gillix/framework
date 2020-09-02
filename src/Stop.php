<?php
    
    namespace glx;
    
    
    use Throwable;

    class Stop extends Exception
    {
        protected        $value;
        protected string $content;
        
        public function __construct($value = null, Throwable $previous = null)
        {
            if ($value !== null) {
                $this->value = $value;
            }
            parent::__construct('', 0, $previous);
        }
        
        public function out(string $content = null): string
        {
            return $this->value ?? parent::out($content);
        }
    }