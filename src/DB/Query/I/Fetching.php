<?php
 
 namespace glx\DB\Query\I;
 
 
 interface Fetching
 {
    public function get($callback = NULL): Result;
    public function one(): Result;
    public function value($column = NULL);
    public function aggregated(array $columns, $page, $pp = NULL): Aggregated;
    public function page($page, $pp = NULL): Paginated;
    public function column($index = NULL): Result;
    public function object($class = NULL, $args = NULL);
    // TODO: + key pair
 }