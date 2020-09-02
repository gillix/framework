<?php
    
    namespace glx\I;
    
    use glx\Locale\I\Calendar;
    use glx\Locale\I\Currency;
    use glx\Locale\I\DateTime;
    use glx\Locale\I\Monetary;
    use glx\Locale\I\Numeric;

    interface Locale
    {
        public function name(): string;
        
        public function language(): string;
        
        public function numeric($number): Numeric;
        
        public function calendar(): Calendar;
        
        public function time($time = null): DateTime;
        
        public function monetary(): Monetary;
        
        public function currency($currency = null): Currency;
        
        public static function get(string $locale): self;
        
        public static function for(string $country): ?self;
        
        public static function list($condition): array;
    }
 