<?php
    
    namespace glx\core\I;
    
    interface Rewriter
    {
        public function extend(array|null $options = null): string|null;
    }
