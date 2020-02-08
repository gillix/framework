<?php
 namespace glx\core\I;

 interface Entity 
 {
    public function id(): ID;
    public function type(): string;
    public function is(string $type, bool $not = false): bool;
    public function not(string $type): bool;
    public function sameAs($other): bool;
 }