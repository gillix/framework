<?php
 
 namespace glx\DB\Query\I;
 
 use glx\Common\I\ObjectAccess;
 
 interface Result extends ObjectAccess
 {
    public function stat(): array;
 }