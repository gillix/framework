<?php
 
 namespace glx\Cache\I;
 
 interface Persistent
 {
    public function get(string $key);
    public function store(string $key, $value, int $lifetime = 0): void;
    public function delete(string $key, bool $search = false): void;
 }
 
 