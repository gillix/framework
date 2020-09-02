<?php
    
    namespace glx\Events;
    
    
    use Closure;
    use glx\Context;

    trait Delegated
    {
        public function on(string $event, Closure $handler): void
        {
            Context::event()->for($this)->on($event, $handler);
        }
        
        public function off(string $event, Closure $handler): void
        {
            Context::event()->for($this)->off($event, $handler);
        }
        
        public function fire(string $event, array $arguments = null): void
        {
            Context::event()->for($this)->fire($event, $arguments);
        }
        
        public function dispatch(I\Event $event): void
        {
            Context::event()->for($this)->dispatch($event);
        }
    }