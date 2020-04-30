<?php
 namespace glx\Locale\I;
 
 interface Monetary
 {
    public function currencies($condition): array;
    public function format($number, $currency = NULL, string $width = NULL, string $kind = 'standard'): string;
 }