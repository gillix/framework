<?php
    
    namespace glx\HTTP;
    
    
    use Throwable;

    class ExternalRedirect extends Redirect
    {
        public function __construct($uri, Throwable|null $previous = null)
        {
            parent::__construct($uri, self::EXTERNAL, $previous);
        }
    }
