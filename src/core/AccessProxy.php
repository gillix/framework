<?php
    
    namespace glx\core;
    
    trait AccessProxy
    {
        public function __call($name, $arguments)
        {
            if ($method = $this->get($name, 'method')) {
                return $method->apply($this->this(), $arguments);
            }
            
            return null;
        }
        
        public function __set($name, $value)
        {
            $this->add($name, $value);
        }
        
        public function __get($name)
        {
            return $this->get($name);
        }
        
        public function __isset($name)
        {
            return $this->has($name);
        }
        
        public function __unset($name)
        {
            $this->remove($name);
        }
        
        public function offsetExists($name): bool
        {
            return $this->__isset($name);
        }
        
        public function offsetGet($name)
        {
            return $this->__get($name);
        }
        
        public function offsetSet($name, $value): void
        {
            $this->__set($name, $value);
        }
        
        public function offsetUnset($name): void
        {
            $this->__unset($name);
        }
    }