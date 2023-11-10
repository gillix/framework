<?php
    
    namespace glx\core\I;
    
    use ArrayAccess;
    use Closure;
    use Countable;
    use IteratorAggregate;

    interface Selection extends IteratorAggregate, ArrayAccess, Countable
    {
        public function add($item): Selection;
        
        public function remove($item): Selection;
        
        public function filter($condition = null): Selection;
        
        public function sort($by, int $way = Sort::ASC): Selection;
        
        public function limit(int $limit, int $offset = 0): Selection;
        
        public function offset($offset): Selection;
        
        public function each($callback, $arguments = null): Selection;

        public function map(Closure $callback): Selection;

        public function extend(Selection $set): Selection;
    }
