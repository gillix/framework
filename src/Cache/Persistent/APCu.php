<?php
 
 namespace glx\Cache\Persistent;
 
 use glx\Cache;
 
 require_once __DIR__.'/../Persistent.php';
 
 class APCu implements Cache\I\Persistent
 {
    public function __construct()
    {
      if(!extension_loaded('apcu'))
        throw new \glx\Exception('Can`t initialise persistent cache. Extension \'apcu\' is not loaded');
    }
 
    public function get(string $key)
    {
      $success = false;
      $value = apcu_fetch($key, $success);
      return $success ? $value : NULL;
    }
  
    public function store(string $key, $value, int $lifetime = 0): void
    {
      // возможно устанавливать время жизни
      apcu_store($key, $value, $lifetime);
    }

    public function delete(string $key, bool $search = false): void
    {
      apcu_delete($search ? new \APCUIterator($key, APC_ITER_ALL, 1000) : $key);
    }
 }
 
 Cache\Persistent::register('apc', APCu::class);