<?php
    
    namespace glx\Events\I;
    
    interface Provider
    {
        public function handlers(string $event): ?iterable;
    }
