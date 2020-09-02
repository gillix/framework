<?php
    
    namespace glx\Cache;
    
    use Symfony\Component\Cache\Psr16Cache;

    class SymfonyCache implements I\Persistent
    {
        protected Psr16Cache $adapter;
        
        public function __construct(Psr16Cache $adapter)
        {
            $this->adapter = $adapter;
        }
        
        public function get(string $key)
        {
            return $this->adapter->get($key);
        }
        
        public function store(string $key, $value, int $lifetime = 0): void
        {
            $this->adapter->set($key, $value, $lifetime);
        }
        
        public function delete(string $key, bool $search = false): void
        {
            $this->adapter->delete($key);
        }
    }