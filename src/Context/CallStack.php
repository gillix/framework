<?php
    
    namespace glx\Context;
    
    use glx\core;
    use SplStack;

    require_once 'I/CallStack.php';
    
    class CallStack implements I\CallStack
    {
        private SplStack $stack;
        
        public function __construct()
        {
            $this->stack = new SplStack();
        }
        
        public function current(): core\I\Joint
        {
            return $this->stack->top();
        }
        
        public function enter(core\I\Joint $method): void
        {
            $this->stack->push($method);
        }
        
        public function release(): void
        {
            $this->stack->pop();
        }
        
        public function stack(): SplStack
        {
            return $this->stack;
        }
        
        public function empty(): bool
        {
            return $this->stack->isEmpty();
        }
        
        public function array(): array
        {
            $stack = [];
            foreach ($this->stack as $method) {
                $stack[] = "{$method->parent()->path()}->{$method->name()}()";
            }
            
            return $stack;
        }
    }