<?php
    
    namespace glx\Context;
    
    
    use ArrayObject;

    class Profile extends ArrayObject implements I\Profile
    {
        protected array $path = [];
        public const DEFAULT = 'default';
        
        public function __construct($path = null)
        {
            if ($path) {
                $this->set($path);
            }
            parent::__construct($this->path, ArrayObject::STD_PROP_LIST);
        }
        
        public function set($path): void
        {
            $this->path = is_string($path) ? explode('.', $path) : $path;
        }
        
        public function add(string $profile): void
        {
            foreach (explode('.', $profile) as $p) {
                array_unshift($this->path, $p);
            }
        }
        
        public function remove(string $profile): void
        {
            foreach (explode('.', $profile) as $p) {
                unset($this->path[array_search($p, $this->path, true)]);
            }
        }
        
        public function __toString()
        {
            return implode('.', $this->path);
        }
    }