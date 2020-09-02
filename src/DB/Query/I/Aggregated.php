<?php
    
    namespace glx\DB\Query\I;
    
    
    interface Aggregated extends Paginated
    {
        public function aggregated(string $field);
    }