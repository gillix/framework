<?php
 
 namespace glx\Config\I;
 
 interface Reader
 {
    public function parse(string $content): array;
    public static function get(string $format = NULL): self;
    public static function default(string $format = NULL): string;
    public static function read(string $path): array;
 }
