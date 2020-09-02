<?php
    
    namespace glx\Events;
    
    
    use Closure;

    trait Support
    {
        private array $handlers;
        
        public function on(string $event, Closure $handler): void
        {
            if (!isset($this->handlers)) {
                $this->handlers = [];
            }
            $this->handlers[$event][] = $handler; // TODO: возможно заменить на приоритетную очередь
        }
        
        public function off(string $event, Closure $handler): void
        {
            if (!isset($this->handlers) || !count($this->handlers)) {
                return;
            }
            if (($handlers = $this->handlers[$event]) && ($i = array_search($handler, $handlers, true))) {
                unset($this->handlers[$event][$i]);
            }
        }
        
        public function fire(string $event, array $arguments = null): void
        {
            if (!isset($this->handlers) || !count($this->handlers[])) {
                return;
            }
            if ($this->handlers($event)) {
                $this->dispatch(new Event($this, $event, $arguments, $this));
            }
        }
        
        public function dispatch(I\Event $event): void
        {
            if (!isset($this->handlers) || !count($this->handlers)) {
                return;
            }
            if ($handlers = $this->handlers($event->name())) {
                foreach ($handlers as $handler) {
                    if ($handler instanceof Closure) {
                        try {
                            if ($handler->call($event, ...$event->data()) === false) {
                                break;
                            }
                        } catch (E\StopPropagation $e) {
                            break;
                        }
                    }
                }
            }
        }
        
        public function handlers(string $event): ?iterable
        {
            return $this->handlers[$event];
        }
    }
