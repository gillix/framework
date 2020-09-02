<?php
    
    namespace glx\DB\I;
    
    interface QueryCompiler
    {
        public function select(iterable $units): array;
        
        public function delete(iterable $units): array;
        
        public function insert(iterable $units): array;
        
        public function update(iterable $units): array;
    }