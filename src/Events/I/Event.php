<?php
    
    namespace glx\Events\I;
    
    use Closure;

    interface Event
    {
        public function emitter(): Emitter;
        
        public function name(): string;
        
        public function data(): array;
        
        public function fire(...$arguments);
        
        public function listen(Closure $handler);
        
        public function stop();
    }
