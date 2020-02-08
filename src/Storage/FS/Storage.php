<?php
 
 namespace glx\Storage\FS;
 
 use glx\Context;
 use glx\core;
 use glx\Storage\Manager;
 use glx\Storage\Exception;
 use glx\Cache;

 include_once 'Structure.php';
 include_once 'Compiler.php';
 include_once 'Manifest.php';
 require_once __DIR__.'/../Loader.php';
 include_once __DIR__.'/../Storage.php';


 class Storage extends \glx\Storage\Storage implements I\Storage
 {
    protected static array $factories = [];
    protected static array $types = [];
    public const DEFAULT_FACTORY = '*';
  
    protected string $mode;
    protected string $key;
    protected I\Structure $structure;
    protected Cache\I\Persistent $cache;
    protected \glx\I\Context $context;
    protected I\Compiler $compiler;
    protected I\Manifest $manifest;
    protected core\I\Entity $root;
    protected static array $locations = ['.', __DIR__.'/../../..'];
  
    public function __construct($options = [], \glx\I\Context $context = NULL)
    {
      $this->mode = $options['mode'] ?? 'production';
      $path = realpath(is_string($options) ? $options : $options['path']);
      $this->key = md5($path);
      if(!$path || !is_dir($path))
        throw new Exception('Storage path is not provided or path is not a directory');
      
      $this->context = $context ?? Context::get();
      $this->cache = $this->context->persistent();
      if(!($manifest = $this->cache->get($this->key('manifest'))))
       {
        $manifest = new Manifest($path);
        if($manifest->load())
          $this->cache->store($this->key('manifest'), $manifest);
        else
          $manifest->init(['storage' => [
           'type' => 'fs',
           'structure' => [
              'source'   => '.src',
              'registry' => '.build/registry',
              'hidden'   => '.build/hidden',
              'public'   => '.build/public/files'
           ]]]);
       }
      if($manifest->package->name)
        \glx\Storage\Manager::register($manifest->package->name, $this);
      $this->manifest = $manifest;
      $this->structure = new Structure($path, $this->manifest->storage->structure->array());
      $this->compiler = new Compiler($this->structure);
      
      parent::__construct($this->manifest->storage->id ?: NULL, (array)$options);
      if(!$this->manifest->storage->id)
        $this->manifest->storage->id = $this->id;
    }
  
    public function fetch(string $id): array
    {
      if(!($record = $this->registry->record($id)))
       {
        if(!($record = $this->cache->get($this->key($id))))
         {
          if(!($record = $this->compiler->fetch($id)))
            throw new Exception('Can`t fetch object with provided ID: ' . $id);
          $this->cache->store($this->key($id), $record);
         }
        $this->registry->add($id, $record);
       }
      return $record;
    }
  
    public function load(string $id): core\I\Entity
    {
      $record = $this->fetch($id);
      if($this->mode === 'dev' && $record['creator'] && $this->include() && class_exists($record['creator']))
        try { $record['creator']::check($record, $this); }
        catch(Factory\RecordChanged $e)
         {
          $record = $e->record();
          // overwrite record with new info
          $this->compiler->store($id, $record);
          $this->cache->store($this->key($id), $record);
          $this->registry->add($id, $record);
         }
      return $record['object'];
    }
  
    public function root(): core\I\Entity
    {
      if(!isset($this->root))
       {
        if(!($id = $this->manifest->storage->root))
         {
          if($this->mode === 'production')
            throw new Exception('Can`t load root object. Trying to use not compiled storage in production mode. Please compile before use or change storage mode to dev');
          $this->compile();
          return $this->root();
         }
        if($root = $this->load($id))
          $this->root = $root;
       }
      if(!isset($this->root))
        throw new Exception('Can`t load root object with stored ID');
      return $this->root;
    }
 
    protected function key(string $suffix): string
    {
      return "storage:{$this->key}:{$suffix}";
    }
  
    public function compile(array $options = NULL)
    {
      // Include only if we need
      $this->include();
      // clear old compiled structure
      $this->cache->delete("/^{$this->key('')}/", true);
      $this->compiler->clear(['registry', 'hidden', 'public']);
      $this->manifest->delete();

      $src = $this->structure->source;
      if(!is_dir($src->path()))
        throw new Exception('Compile failed: source directory is not found');

      if($options)
        $this->manifest->init($options);
      if($root = $this->produce(['path' => $src->path()], $src))
       {
        $this->root = $root['object'];
        $this->manifest->storage->id = $this->id();
        $this->manifest->storage->root = $root['object']->id()->object();
        $this->manifest->build->time = date('Y-m-d H:i:s');
        $this->manifest->build->php = 'PHP '.PHP_VERSION;
        $this->manifest->build->os = php_uname();
        $this->manifest->store();
        $this->structure = new Structure($this->structure->path(), $this->manifest->storage->structure->array());
       }
      else
        throw new Exception('Compile failed: root object not loaded');
    }
  
    public function forget(string $id): void
    {
      $this->registry->remove($id);
      $this->cache->delete($this->key($id));
      $this->compiler->delete($id);
    }
    
    public function produce(array $info, I\Structure $current): ?array
    {
      if($info['extension'])
       $factories = self::$types[$info['extension']];
      if(!$factories)
        $factories = self::$types[self::DEFAULT_FACTORY];
      if($factories)
        foreach($factories as $pretender)
          if($pretender::probe($info, $current))
            $factory = $pretender;
      if(!$factory)
        foreach(self::$factories as $pretender)
          if($pretender::probe($info, $current))
            $factory = $pretender;
      if(!class_exists($factory)) return NULL;
      if($record = $factory::create($info, $current, $this))
       {
        $this->compiler->store($record['object']->id()->object(), $record);
        return $record;
       }
      return NULL;
    }
  
    protected function include(): bool
    {
      if(!count(self::$factories))
        foreach(new \DirectoryIterator(__DIR__.'/Factory') as $factory)
         {
          if(in_array($factory->getFilename(), ['.', '..'])) continue;
          include_once $factory->getPathname();
         }
      return (bool)count(self::$factories);
    }
  
    public function structure(): I\Structure
    {
      return $this->structure;
    }
  
    public function compiler(): I\Compiler
    {
      return $this->compiler;
    }
  
    public static function factory(array $types, $factory): void
    {
      self::$factories[] = $factory;
      // Index by supported file extensions
      foreach($types as $type)
        self::$types[$type][] = $factory;
    }
  
    public static function locations(array $locations = NULL, bool $append = false): array
    {
      if($locations)
        self::$locations = $append ? array_merge(self::$locations, $locations) : $locations;
      return self::$locations;
    }
  
    public static function location(string $location): void
    {
      self::$locations[] = $location;
    }
  
    public static function valid(string $location): bool
    {
      return is_dir($location) && is_file($location . DIRECTORY_SEPARATOR . Manifest::$fileName);
    }
  
 }
 
 Manager::storage('fs', Storage::class);
 Manager::autoloader(function(string $label, ?array $options = NULL): \glx\Storage\I\Storage {
    $options = $options ?? [];
    if(Storage::valid($label))
      return Storage::new(array_merge(['path' => $label], $options));
    if(strpos('/', $label) === 0) return NULL;
//    if($slash !== false)
//      [$label, $rest] = explode('/', $label, 2);
    $locations = Storage::locations();
    foreach($locations as $location)
      do
       {
        if(Storage::valid($target = $location . DIRECTORY_SEPARATOR . $label))
          return Storage::new(array_merge(['path' => $target], $options));
       }
      while($location !== ($new = realpath($location . DIRECTORY_SEPARATOR . '..')) && ($location = $new));

    return NULL; // исключение
 }, ['fs', Manager::STORAGE_ANY]);