<?php
    
    namespace glx\Events;
    
    
    use Closure;
    use glx\Exception;

    class Event implements I\Event
    {
        protected I\Dispatcher $dispatcher;
        protected I\Emitter    $emitter;
        protected string       $name;
        protected array        $data;
        protected bool         $stopped = false;
        
        public function __construct(I\Dispatcher $dispatcher, string $name, array $data = null, I\Emitter $emitter = null)
        {
            $this->dispatcher = $dispatcher;
            $this->name = $name;
            if ($emitter) {
                $this->emitter = $emitter;
            }
            if ($data) {
                $this->data = $data;
            }
        }
        
        public function emitter(): I\Emitter
        {
            if (isset($this->emitter)) {
                return $this->emitter;
            }
            if ($this->dispatcher instanceof I\Emitter) {
                return $this->dispatcher;
            }
            throw new Exception('Event emitter not exist');
        }
        
        public function name(): string
        {
            return $this->name;
        }
        
        public function fire(...$arguments): void
        {
            if ($arguments) {
                $this->data = $arguments;
            }
            $this->dispatcher->dispatch($this);
        }
        
        public function listen(Closure $handler): void
        {
            $this->dispatcher->on($this->name(), $handler);
        }
        
        /**
         * @throws E\StopPropagation
         */
        public function stop(): void
        {
            $this->stopped = true;
            throw new E\StopPropagation();
        }
        
        public function data(): array
        {
            return $this->data ?? [];
        }
    }
