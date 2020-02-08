<?php
 
 namespace glx\DB\I;
 
 
 
 interface Driver extends Connection
 {
    public function perform(\Closure $execute, $query, ?array $values = NULL);
    public function compiler(): QueryCompiler;
 }