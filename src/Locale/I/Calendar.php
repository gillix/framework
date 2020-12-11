<?php
    
    namespace glx\Locale\I;
    
    interface Calendar
    {
        public function format(\DateTimeInterface $time, $format = DateTime::FORMAT_LONG): string;
        public function formatDate(\DateTimeInterface $time, $format = DateTime::FORMAT_LONG): string;
        public function formatTime(\DateTimeInterface $time, $format = DateTime::FORMAT_LONG): string;
        public function formatInterval(\DateTimeInterface $from, \DateTimeInterface $to, $format = DateTime::FORMAT_LONG): string;
        // TODO: full interface
    }
