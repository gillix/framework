<?php
 
 namespace glx\HTTP\I;
 
 
 interface URI
 {
    public function port(string $value = NULL): string;
    public function scheme(string $value = NULL): string;
    public function host(string $value = NULL): string;
    public function path(string $value = NULL): string;
    public function query($value = NULL): Query;
    public function fragment(string $value = NULL): string;
    public function user(string $value = NULL): string;
    public function pass(string $value = NULL): string;
    public function get(string $name);
    public function parts(array $value = NULL): array;
    public function has(string $name): bool;
    public function __toString();
 }