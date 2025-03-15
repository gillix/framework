<?php
    
    namespace glx\HTTP;
    
    
    use Exception;
    use Throwable;

    class Redirect extends Exception
    {
        public const AUTO     = 0;
        public const INTERNAL = 1;
        public const EXTERNAL = 2;
        protected int   $mode;
        protected I\URI $uri;
        
        public function __construct($uri, int $mode = self::AUTO, Throwable|null $previous = null)
        {
            $this->uri = new URI($uri);
            $this->mode = $mode;
            parent::__construct('', 0, $previous);
        }
        
        public function mode(): int
        {
            return $this->mode;
        }
        
        public function uri(): I\URI
        {
            return $this->uri;
        }
    }
