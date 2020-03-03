<?php
 
 namespace glx\DB\Query\I;
 
 use glx\Common\I\Collection;
 
 interface Result extends Collection
 {
    public function stat(): array;
 }