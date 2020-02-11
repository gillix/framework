<?php
 namespace glx\Session\ID;

 class Custom extends Provider
 {
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
 
    public function create(int $lifetime = 0): string
    {
      return $this->id = $this->generate();
    }
   
    public function delete(): void {}
 }