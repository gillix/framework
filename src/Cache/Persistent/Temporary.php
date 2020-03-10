<?php
 namespace glx\Cache\Persistent;

 use glx\Cache;
 use Symfony\Component\Cache\Adapter\ArrayAdapter;
 use Symfony\Component\Cache\Psr16Cache;

 class Temporary extends Cache\SymfonyCache
 {
    public function __construct(array $options = [])
    {
      parent::__construct(new Psr16Cache(new ArrayAdapter()));
    }
 }
 