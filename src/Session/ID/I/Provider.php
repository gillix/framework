<?php
 namespace glx\Session\ID\I;
 
 
 interface Provider
 {
    public function id(): string;
    public function exist(): bool;
    public function create(int $lifetime = 0, array $options = []): string;
    public function delete(): void;
    public function __toString();
 }