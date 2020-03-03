<?php
 namespace glx\Common\I;

 interface Entity extends Resource
 {
    public function id();
    public function sameAs($other): bool;
 }