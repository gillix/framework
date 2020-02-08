<?php
 namespace glx\Locale\I;
 
 interface Calendar
 {
    public function format($time, $format = NULL): string;
    // TODO: full interface
 }
