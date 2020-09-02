<?php
    
    namespace glx\HTTP;
    
    
    use Throwable;

    class InternalRedirect extends Redirect
    {
        public function __construct($uri, Throwable $previous = null)
        {
            parent::__construct($uri, self::INTERNAL, $previous);
        }
    }
 