<?php
 
 namespace glx\Cache\I;
 
 interface Persistent
 {
    public function get(string $key);
    public function store(string $key, $value);
    public function delete(string $key, bool $search = false);
 }
 
 