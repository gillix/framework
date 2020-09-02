<?php
    
    namespace glx\DB\Query\I;
    
    
    interface Paginated extends Result
    {
        public function total(): int;
        
        public function page(): int;
        
        public function perPage(): int;
        
        public function from(): int;
        
        public function to(): int;
    }