<?php
 namespace glx\HTTP;
 

 class ExternalRedirect extends Redirect
 {
    public function __construct($uri, \Throwable $previous = NULL)
    {
      parent::__construct($uri, self::EXTERNAL, $previous);
    }
 }
 