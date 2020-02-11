<?php

 namespace glx\Storage\FS;
 
 
 class Structure implements I\Structure
 {
    protected const MASK = '0755';
  
    protected string $path;
    protected array $sections = [];
    protected string $relative = '';
  
    public function __construct(string $path, $relative = NULL)
    {
      $this->path = $path;
      if(is_array($relative))
        $this->sections = $relative;
      elseif(is_string($relative))
        $this->relative = implode('/', array_filter(explode('/', $relative)));
    }
 
    public function add(string $section, string $relative): void
    {
      $this->sections[$section] = $relative;
    }
  
    public function path(string $relative = NULL, string $section = NULL): string
    {
      return implode('/', array_filter([$this->section($section), $this->relative, $relative]));
    }
  
    public function relative(string $relative = NULL): string
    {
      return implode('/', array_filter([$this->relative, $relative]));
    }
  
    protected function section(string $name = NULL): string
    {
      return $this->path . ($name && $this->sections[$name] ? '/'.$this->sections[$name] : NULL);
    }
  
    protected static function mkdir($path, $mask): bool
    {
      if(!is_dir($parent = dirname($path))) self::mkdir($parent, $mask);
      if(!is_dir($path) && !mkdir($path, $mask) && !is_dir($path))
        return false;
      return true;
    }
  
    protected static function clear($path): void
    {
      if(is_file($path)) $path = dirname($path);
      if(!is_dir($path)) return;
      $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
      foreach($it as $file)
        if($file->isDir()) rmdir($file->getPathname());
        else unlink($file->getPathname());
    }
  
    public function implement($mask = NULL): bool
    {
      $mask = $mask ?? self::MASK;
      $path = $this->path();
      if(!is_dir($path))
        return self::mkdir($path, $mask);
      return true;
    }
  
    public function destruct(): void
    {
      self::clear($this->path());
    }
  
    public function get($name): I\Structure
    {
      if($this->sections[$name])
        return new self($this->section($name));
      else
        return new self($this->path, implode('/', array_filter([$this->relative, $name])));
    }

    public function __get(string $name): I\Structure
    {
      return $this->get($name);
    }

    public function __set($name, $value)
    {
      $this->add($name, $value);
    }

    public function __isset($name)
    {
      return array_key_exists($name, $this->sections);
    }
 }