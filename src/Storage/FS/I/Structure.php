<?php
 namespace glx\Storage\FS\I;

 interface Structure 
 {
    public function __get(string $section): Structure;
    public function get(string $section): Structure;
    public function add(string $section, string $path): void;
    public function path(string $relative = NULL): string;
    public function relative(string $relative = NULL): string;
    public function implement($mask = NULL): bool;
    public function destruct(): void;
 }