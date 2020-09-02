<?php
    
    namespace glx\Locale\I;
    
    interface Numeric
    {
        public function format($precision = null): string;
//    public function asText($number): array;
    }
 