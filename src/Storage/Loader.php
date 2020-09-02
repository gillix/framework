<?php
    
    namespace glx\Storage;
    
    use glx\core;
    use glx\Storage;

    class Loader extends core\Binder implements core\I\Entity
    {
        private core\I\ID     $id;
        private core\I\Binder $binder;
        
        public function __construct(core\Binder $binder)
        {
            $this->id = $binder->origin()->id();
            $binder->_origin = $this;
            $this->binder = $binder;
        }
        
        public function id(): core\I\ID
        {
            return $this->id;
        }
        
        public function is(string $type, bool $not = false): bool
        {
            return call_user_func([$this->replace(), __FUNCTION__], $type, $not);
        }
        
        public function not(string $type): bool
        {
            return call_user_func([$this->replace(), __FUNCTION__], $type);
        }
        
        public function type(): string
        {
            return call_user_func([$this->replace(), __FUNCTION__]);
        }
        
        public function name(): string
        {
            return $this->binder->name();
        }
        
        public function visibility(): int
        {
            return $this->binder->visibility();
        }
        
        public function sameAs($other): bool
        {
            return (string)$this->id() === (string)$other->id();
        }
        
        public function __call($name, $arguments)
        {
            return call_user_func_array([$this->replace(), $name], $arguments);
        }
        
        public function __get($name)
        {
            return $this->replace()->{$name};
        }
        
        public function __set($name, $value)
        {
            return $this->replace()->{$name} = $value;
        }
        
        public function __isset($name)
        {
            return isset($this->replace()->{$name});
        }
        
        protected function replace(): core\I\Entity
        {
            if (!($this->binder->_origin instanceof self)) {
                return $this->binder->_origin;
            }
            $object = Storage\Manager::get($this->id()->storage())->load($this->id()->object());
            $this->binder->_origin = $object;
            
            return $object;
        }
        
        public function _cheat(core\I\Joint $joint = null)
        {
            (new class($this->replace()) extends core\Cheater {
                public function __construct(core\Cheater $object) { $this->_origin = $object; }
                
                public function _cheat(core\I\Joint $joint = null) { $this->_origin->_cheat($joint); }
            })->_cheat($joint);
        }
        
        public function __toString()
        {
            $origin = $this->replace();
            if (method_exists($origin, '__toString')) {
                return (string)$origin;
            }
            throw new Exception('Can`t convert to String object of ' . get_class($origin));
        }
    }