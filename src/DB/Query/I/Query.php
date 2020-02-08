<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Query
 {
    public function __toString(): string;
    public function compile(): array;
//    public function entries(): array;
 }
 