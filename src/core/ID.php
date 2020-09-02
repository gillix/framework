<?php
    
    namespace glx\core;
    
    class ID implements I\ID
    {
        protected string $storage;
        protected string $object;
        private string   $cache = '';
        
        public function __construct(string $storage, string $object)
        {
            $this->storage = $storage;
            $this->object = $object;
        }
        
        public function storage(): string
        {
            return $this->storage;
        }
        
        public function object(): string
        {
            return $this->object;
        }
        
        public function __toString()
        {
            if (!$this->cache) {
                $this->cache = "{$this->storage}:{$this->object}";
            }
            
            return $this->cache;
        }
    }