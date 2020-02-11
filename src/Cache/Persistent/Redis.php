<?php
 namespace glx\Cache\Persistent;

 use glx\Cache;
 
 class Redis implements Cache\I\Persistent
 {
 
    public function get(string $key)
    {
     // TODO: Implement get() method.
    }
   
    public function store(string $key, $value, int $lifetime = 0): void
    {
     // TODO: Implement store() method.
    }
   
    public function delete(string $key, bool $search = false): void
    {
     // TODO: Implement delete() method.
    }
 }
 