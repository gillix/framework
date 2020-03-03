<?php
 namespace glx\Common\I;

 interface Collection extends ObjectAccess, \ArrayAccess
 {
    public function array(): array;
    public function link(Collection $another): void;
 }