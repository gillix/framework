<?php
    
    namespace glx\Events\I;
    
    use Closure;

    interface Dispatcher
    {
        /** Fire the event
         * @param string $event
         * @param array|NULL $arguments
         */
        public function fire(string $event, array|null $arguments = null): void;
        
        /** Process event
         * @param Event $event
         */
        public function dispatch(Event $event): void;
        
        public function on(string $event, Closure $handler): void;
        
        public function off(string $event, Closure $handler): void;
    }
