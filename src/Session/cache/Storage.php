<?php
 namespace glx\Session\cache;

 use glx\Cache;
 use glx\Context;
 use glx\Exception;
 use glx\Session;
 
 class Storage implements Session\I\Storage
 {
    protected Cache\I\Persistent $cache;
    
    public function __construct()
    {
      $this->cache = Context::persistent();
      if(!$this->cache)
        throw new Exception('Can`t initialise cache session storage: cache is not configured in Context');
    }
 
    public function read(string $id): array
    {
      $stored = $this->cache->get($this->key($id));
      return $stored ? $stored['data'] ?? [] : [];
    }
   
    public function write(string $id, array $data, int $lifetime = NULL): void
    {
      $this->cache->store($this->key($id), ['data' => $data, 'lifetime' => $lifetime], $lifetime);
    }
   
    public function delete($id): void
    {
      $this->cache->delete($this->key($id));
    }
  
    protected function key(string $id): string
    {
      return "session:{$id}";
    }
 
    public function exist($id): bool
    {
      return $this->cache->get($this->key($id)) !== NULL;
    }
 
    public function relocate(string $old, string $new): void
    {
      if($data = $this->read($this->key($old)))
        $this->write($this->key($new), $data, $data['lifetime']);
      $this->delete($this->key($old));
    }
   
    public function clear(int $lifetime): void {}
 }