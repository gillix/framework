<?php
 
 namespace glx\DB\I;
 
 
 
 interface Connection
 {
    public function connect();
    public function disconnect();
    public function connected(): bool;
    public function query($query, ?array $values = NULL, $fetch = NULL);
    public function execute($query, ?array $values = NULL);
//    public function prepare($query);
    public function lastID();
 }