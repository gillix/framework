<?php

 namespace glx\Storage\FS\Factory;
 
 class RecordChanged extends \Exception
 {
    private $record;
  
    public function __construct(array $record)
    {
      $this->record = $record;
    }
  
    public function record(): array
    {
     return $this->record;
    }
 }