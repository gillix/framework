<?php
 namespace glx\HTTP;
 

 class Status extends \glx\Exception
 {
    public function __construct(int $code = I\Response::OK, \Throwable $previous = NULL)
    {
      parent::__construct('', $code, $previous);
    }
 }