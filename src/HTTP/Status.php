<?php
    
    namespace glx\HTTP;
    
    
    use glx\Exception;
    use Throwable;

    class Status extends Exception
    {
        public function __construct(int $code = I\Response::OK, Throwable|null $previous = null)
        {
            parent::__construct('', $code, $previous);
        }
    }
