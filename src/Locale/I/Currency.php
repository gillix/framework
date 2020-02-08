<?php
 namespace glx\Locale\I;
 
 interface Currency
 {
    public function code(): string;
    public function symbol(): string;
    public function name(): string;
    public function format($number, string $width = '', string $kind = 'standard'): string;
    public function for($quantity): string;
 }