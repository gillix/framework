<?php
 
 namespace glx\DB\Query\I;
 
 
 interface JoinCondition
 {
    public function init($condition, string $type = 'on');
    public function inited(): bool;
    public function condition(): array;
 }
 