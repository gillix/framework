<?php
 namespace glx\core\I;

 interface Selection extends \IteratorAggregate, \ArrayAccess, \Countable
 {
    public function add($item): Selection;
    public function remove($item): Selection;
    public function filter($condition = NULL): Selection;
    public function sort($by, int $way = Sort::ASC): Selection;
    public function limit(int $limit, int $offset = 0): Selection;
    public function offset($offset): Selection;
    public function each($callback, $arguments = NULL): Selection;
    public function extend(Selection $set): Selection;
 }