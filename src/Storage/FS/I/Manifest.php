<?php
 namespace glx\Storage\FS\I;

 interface Manifest 
 {
    public function load(): bool;
    public function store(): void;
    public function delete(): void;
    public function init(array $options): void;
 }