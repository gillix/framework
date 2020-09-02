<?php
    
    namespace glx\Locale\I;
    
    interface DateTime
    {
        public function format($format = null): string;
        // TODO: full format
    }