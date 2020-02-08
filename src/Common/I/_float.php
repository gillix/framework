<?php
 
 namespace glx\Common\I;
 
 interface _float extends _number
 {
    public const ROUND_HALF = 0;
    public const ROUND_DOWN = 1;
    public const ROUND_UP   = 2;
    
    public function round(int $mode = self::ROUND_HALF, int $precision = 0): bool;
    public function int(): _integer;
    public function get(): float;
 }
