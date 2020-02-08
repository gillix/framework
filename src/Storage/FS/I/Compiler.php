<?php
 namespace glx\Storage\FS\I;

 interface Compiler 
 {
    public function fetch(string $id): ?array;
    public function store(string $id, array $content);
    public function read(string $path, string $target = 'cmp');
    public function write(string $path, $content, string $target = 'cmp'): void;
    public function delete(string $path, string $target = 'cmp'): void;
    public function clear($section = 'cmp'): void;
 }