<?php
 
 namespace glx\Common\I;
 
 interface _string
 {
    public function trim($chars = NULL): _string;
    public function length(): int;
    public function contains(string $find): bool;
    public function position(string $find): int;
    public function substring(int $start, int $limit = 0): _string;
    public function replace($old, string $new = NULL): _string;
    public function split(string $delimiter): array;
    public function concat(string $other): _string;
    public function lower(): _string;
    public function upper(): _string;
    public static function format(string $format, ...$arguments): _string;
 }
 
