<?php
 namespace glx\DB\Query\I;
 
 
 interface Writable extends Query
 {
    public function perform(): int;
 }