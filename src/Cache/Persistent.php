<?php
 
 namespace glx\Cache;
 
 require_once 'I/Persistent.php';
 
 class Persistent implements I\Persistent
 {
    private static array $implementations;
    /** @var I\Persistent */
    private I\Persistent $implementation;
  
    public function __construct(array $options = [])
    {
      $implementation = $options['storage'] ?? 'apc';
      $implementation = self::$implementations[$implementation];
      if(!$implementation)
        $implementation = self::$implementations['apc']; // логировать warning
      if(!$implementation)
        throw new \glx\Exception('Can`t load cache implementation');
      else
        $this->implementation = new $implementation();
    }
 
    public function get(string $key)
    {
      return $this->implementation->get($key);
    }
 
    public function store(string $key, $value, int $lifetime = 0): void
    {
      $this->implementation->store($key, $value, $lifetime);
    }
  
    public function delete(string $key, bool $search = false): void
    {
      $this->implementation->delete($key, $search);
    }
 
    public static function register(string $label, string $class): void
    {
      self::$implementations[$label] = $class;
    }
 }
 
 foreach(new \DirectoryIterator(__DIR__.'/Persistent') as $factory)
  {
   if(in_array($factory->getFilename(), ['.', '..'])) continue;
   include_once $factory->getPathname();
  }
 