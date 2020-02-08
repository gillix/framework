<?php
 namespace glx\HTTP;
 

 class InternalRedirect extends Redirect
 {
    public function __construct($uri, \Throwable $previous = NULL)
    {
      parent::__construct($uri, self::INTERNAL, $previous);
    }
 }
 