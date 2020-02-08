<?php
 
 namespace glx\Library\I;
 
 
 interface Factory
 {
    public function has(string $id): bool;
    public function get(string $id, $default = NULL);
    public function new(string $id, $default = NULL, array $arguments = NULL);
    public function set(string $id, $maker): void;
    public function use(Factory $other): void;
 }