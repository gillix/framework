<?php
 namespace glx\Events\E;
 
 use Throwable;

 class StopPropagation extends \Exception
 {
    public function __construct()
    {
      parent::__construct($message, $code, $previous);
    }
 }