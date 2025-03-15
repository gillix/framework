<?php
    
    namespace glx\core\I;
    
    interface Joint extends Binder
    {
        public function parent(): self|null;
        
        public function closest($type): self|null;
        
        public function location(): string;
        
        public function path(array|null $options = null): string;
        
        public function childOf(self $parent): bool;
        
        public function root(): self;
    }
