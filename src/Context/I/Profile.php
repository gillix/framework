<?php
 
 namespace glx\Context\I;
 
 interface Profile
 {
    public function set($path): void;
    public function add(string $profile): void;
    public function remove(string $profile): void;
    public function __toString();
 }
