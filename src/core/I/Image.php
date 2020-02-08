<?php
 namespace glx\core\I;

 interface Image extends File, Printable
 {
    public function dimensions(): array;
 }