<?php
 namespace glx;
 
 use Throwable;

 class Exception extends \RuntimeException
 {
    protected string $content;
  
    public function __construct($message = '', $code = 0, Throwable $previous = NULL, string $content = NULL)
    {
      parent::__construct($message, $code, $previous);
      if($content)
        $this->out($content);
    }
  
    public function out(string $content = NULL): string
    {
      if($content)
        $this->content = ($this->content ?? '').$content;
      return $this->content ?? '';
    }
 }