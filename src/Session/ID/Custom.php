<?php
 namespace glx\Session\ID;

 class Custom implements I\Provider
 {
    protected string $id;
  
    public function __construct(string $id = NULL)
    {
      $this->id = $id ?? $this->create();
    }
 
    public function id(): string
    {
      return $this->id;
    }
   
    public function exist(): bool
    {
      return $this->id !== NULL;
    }
 
    public function create(): string
    {
      return $this->id = md5(uniqid(mt_rand(), true));
    }
   
    public function delete(): void
    {
    
    }
 }