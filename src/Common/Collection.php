<?php
    
    namespace glx\Common;
    
    use ArrayIterator;
    use Countable;
    use IteratorAggregate;

    require_once 'I/ObjectAccess.php';
    
    
    class Collection implements I\Collection, IteratorAggregate, Countable
    {
        protected ?array $content = null;
        protected ?array $linked  = null;
        
        public function __construct(array &$array)
        {
            $this->content = &$array;
        }
        
        public function __get($name)
        {
            return (is_array($this->content[$name] ?? null) ? new self($this->content[$name]) : $this->content[$name]) ?? $this->checkLinked($name) ?? null;
        }
        
        protected function checkLinked($name, $isset = false)
        {
            if ($this->linked === null) {
                return null;
            }
            foreach ($this->linked as $linked) {
                if (($isset && ($result = isset($linked[$name]))) || ($result = $linked[$name])) {
                    return $result;
                }
            }
            
            return null;
        }
        
        public function __set($name, $value)
        {
            $this->content[$name] = $value;
        }
        
        public function __isset($name): bool
        {
            return isset($this->content[$name]) || $this->checkLinked($name, true);
        }
        
        public function __unset($name)
        {
            unset($this->content[$name]);
        }
        
        public function offsetExists($name): bool
        {
            return $this->__isset($name);
        }
        
        public function offsetGet(mixed $name): mixed
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
        
        public function array(bool $linked = false): array
        {
            if ($linked && $this->linked) {
                return array_merge($this->content, ...array_map(fn($item) => $item->array(true), $this->linked));
            }
            
            return $this->content;
        }
        
        public function getIterator(): \Traversable
        {
            return new ArrayIterator($this->content);
        }
        
        public function count(): int
        {
            return count($this->content);
        }
        
        public function link(I\Collection $another): void
        {
            if ($this->linked === null) {
                $this->linked = [];
            }
            $this->linked[] = $another;
        }
        
    }
