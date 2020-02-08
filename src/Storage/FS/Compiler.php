<?php
 
 namespace glx\Storage\FS;
 
 use glx\Storage;

 
 class Compiler implements I\Compiler
 {
    protected $structure;
  
    public function __construct(I\Structure $structure)
    {
      $this->structure = $structure;
    }
 
    public function fetch(string $id): ? array
    {
      if($content = $this->read($id))
        return unserialize($content, ['allowed_classes' => true]);
      return NULL;
    }
    
    public function store(string $id, array $content): void
    {
      $this->write($id, serialize($content));
    }

    public function read(string $relative, string $target = 'registry')
    {
      $path = $this->structure->get($target)->path($relative);
      if(is_file($path))
        return file_get_contents($this->structure->get($target)->path($relative));
      return NULL;
    }
  
    public function write(string $relative, $content, string $target = 'registry'): void
    {
      $this->structure->get($target)->get(dirname($relative))->implement();
      file_put_contents($this->structure->get($target)->path($relative), $content);
    }
  
    public function delete(string $relative, string $target = 'registry'): void
    {
      unlink($this->structure->get($target)->path($relative));
    }

    public function clear($section = 'registry'): void
    {
      if(is_array($section))
        foreach($section as $item)
          $this->clear($item);
      else
        $this->structure->get($section)->destruct();
    }
 }