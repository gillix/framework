<?php
    
    namespace glx;
    
    use RuntimeException;
    use Throwable;

    class Exception extends RuntimeException
    {
        protected string $content;
        
        public function __construct($message = '', $code = 0, Throwable $previous = null, string $content = null)
        {
            parent::__construct($message, $code, $previous);
            if ($content) {
                $this->out($content);
            }
        }
        
        public function out(string $content = null): string
        {
            if ($content) {
                $this->content = ($this->content ?? '') . $content;
            }
            
            return $this->content ?? '';
        }
    }