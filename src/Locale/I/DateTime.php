<?php
 namespace glx\Locale\I;
 
 interface DateTime
 {
    public function format($format = NULL): string;
    // TODO: full format
 }