<?php
    
    namespace glx\Locale\I;
    
    interface DateTime
    {
        public const FORMAT_FULL = 'full';
        public const FORMAT_LONG = 'long';
        public const FORMAT_MEDIUM = 'medium';
        public const FORMAT_SHORT = 'short';
        
        public function format($format = self::FORMAT_LONG): string;
        public function formatDate($format = self::FORMAT_LONG): string;
        public function formatTime($format = self::FORMAT_LONG): string;
    }