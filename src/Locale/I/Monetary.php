<?php
    
    namespace glx\Locale\I;
    
    interface Monetary
    {
        public function currencies($condition): array;
        
        public function format($number, $currency = null, string $width = '', string $kind = 'standard'): string;
    }