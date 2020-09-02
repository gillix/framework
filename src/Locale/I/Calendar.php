<?php
    
    namespace glx\Locale\I;
    
    interface Calendar
    {
        public function format($time, $format = null): string;
        // TODO: full interface
    }
