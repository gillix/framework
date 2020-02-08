<?php
 
 namespace glx\DB\I;
 
 interface UnitsCompiler
 {
    public function columns($data): string;
    public function where($data): string;
    public function order($data): string;
    public function limit($data): string;
    public function offset($data): string;
    public function join($data): string;
    public function table($data): string;
    public function from($data): string;
    public function group($data): string;
    public function having($data): string;
    public function fields($data): string;
    public function set($data): string;
    public function values($data): string;
    public function bindings(): array;
 }