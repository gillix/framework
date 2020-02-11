<?php
 
 namespace glx\Session\I;
 
 
 interface Storage
 {
    public function read(string $id): array;
    public function write(string $id, array $data, int $lifetime = NULL): void;
    public function exist($id): bool;
    public function delete($id): void;
    public function relocate(string $old, string $new): void;
    public function clear(int $lifetime): void;
 }
 