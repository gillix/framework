<?php
    
    namespace glx\core\I;
    
    use ArrayAccess;
    use Closure;
    use Countable;
    use IteratorAggregate;

    interface Selection extends IteratorAggregate, ArrayAccess, Countable
    {
        public function add($item): self;
        
        public function remove($item): self;
        
        public function filter($condition = null): self;
        
        public function sort($by, int $way = Sort::ASC): self;
        
        public function limit(int $limit, int $offset = 0): self;
        
        public function offset($offset): self;
        
        public function each($callback, $arguments = null): self;

        public function map(Closure $callback): self;

        public function extend(self $set): self;

        public function array(): array;
    }
