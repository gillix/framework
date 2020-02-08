<?php
 
 namespace glx\HTTP\I;
 
 
 interface Cookie
 {
    public function delete($name): void;
    public function has(string $name): bool;
    public function get(string $name);
    public function set($name, $value, $lifetime = NULL, string $path = NULL, string $domain = NULL, bool $secure = NULL, bool $httponly = NULL): void;
    public function apply(): void;
 }