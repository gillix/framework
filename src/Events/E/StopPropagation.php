<?php
    
    namespace glx\Events\E;
    
    use Exception;

    class StopPropagation extends Exception
    {
        public function __construct()
        {
            parent::__construct($message, $code, $previous);
        }
    }