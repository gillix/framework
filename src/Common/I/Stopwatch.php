<?php
 
 namespace glx\Common\I;
 
 interface Stopwatch
 {
    public static function start(): self;
    public function finish(): _float;
    public function elapsed(string $label = NULL, string $from = NULL): _float;
    public function stat(): array;
    public function __toString();
 }