<?php
 
 namespace glx\Storage\FS;

 use glx\Common;
 use glx\Storage;

 class Manifest extends Common\ObjectAccess implements I\Manifest
 {
    protected string $path;
    protected static array $template = ['package' => [], 'storage' => ['structure' => []], 'build' => []];
    public static string $fileName = '.manifest';
  
    public function __construct(string $path, array $options = NULL)
    {
      $this->path = is_dir($path) ? $path.DIRECTORY_SEPARATOR.self::$fileName : $path;
      $content = self::$template;
      parent::__construct($content);
      if($options) $this->init($options);
    }
  
    public function load(): bool
    {
      if(!is_file($this->path)) return false;
      try { $this->content = json_decode(@file_get_contents($this->path), true, 512, JSON_THROW_ON_ERROR); }
      catch(\Exception $e) { return false; }
      return true;
    }
  
    public function store(): void
    {
      try { @file_put_contents($this->path, json_encode($this->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR )); }
      catch(\Exception $e) { throw new Storage\Exception('Can`t save manifest file: '.$e->getMessage(), 0, $e); }
    }
  
    public function delete(): void
    {
      try { @unlink($this->path); } catch(\Exception $e) {}
    }
  
    public function init(array $options): void
    {
      if(is_array($options['package']))
        foreach($options['package'] as $name => $value)
          if(!is_array($value))
            $this->content['package'][$name] = $value;
      if(is_array($options['storage']))
        foreach($options['storage'] as $name => $value)
          if(!is_array($value))
            $this->content['storage'][$name] = $value;
      if(is_array($options['storage']['structure']))
        foreach($options['storage']['structure'] as $name => $value)
          if(!is_array($value))
            $this->content['storage']['structure'][$name] = $value;
    }
    
    public function __set($name, $value)
    {
      if(!is_array($value))
        parent::__set($name, $value);
      elseif(is_array($this->content[$name]))
        throw new Storage\Exception('You can`t modify a section of manifest');
    }
 }
 
 
