<?php
 namespace glx\Storage\I;

 interface Manager 
 {
    public static function get($label, array $options = NULL): ?Storage;
    public static function register(string $label, $storage);
 }