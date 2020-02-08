<?php
 namespace glx;
 

 class Stop extends Exception
 {
    protected $value;
    protected string $content;
    
    public function __construct($value = NULL, \Throwable $previous = NULL)
    {
      if($value !== NULL)
        $this->value = $value;
      parent::__construct('', 0, $previous);
    }
 
    public function out(string $content = NULL): string
    {
      return $this->value ?? parent::out($content);
    }
 }