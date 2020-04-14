<?php
 
 namespace glx\Storage\FS\Factory;
 
 use glx\Storage;
 use glx\core;

 class Property extends Storage\FS\Factory
 {

    public static function probe(array $info, Storage\FS\I\Structure $current): bool
    {
      $val = $info['content'];
      $extension = $info['extension'];
      if((!$extension && isset($info['content']) && (is_bool($val) || is_int($val) || is_float($val) || is_string($val)))
       || ($extension && in_array($extension, ['float', 'int', 'bool', 'string', 'str'])))
        return true;
      return false;
    }
  
    public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
    {
      $record['creator'] = self::class;

      $value = null;
      // loading value of property
      if(isset($info['content']))
       {
        // if creates from parent .node definition
        $value = $info['content'];
        $record['source'] = $info['source'];
       }
      elseif($info['file'] && is_file($path = $info['path']))
       {
        // if creates from file
        $value = file_get_contents($path);
        $record['source'] = $current->relative($info['file']);
       }
     
      // Detect type of property
      $extension = $info['extension'];
      if((!$extension && is_bool($value)) || $extension === 'bool')
        $class = core\Boolean::class;
      elseif((!$extension && is_int($value)) || $extension === 'int')
        $class = core\Integer::class;
      elseif((!$extension && is_float($value)) || $extension === 'float')
        $class = core\FloatNumber::class;
      elseif((!$extension && is_string($value)) || $extension === 'string' || $extension === 'str')
        $class = core\Str::class;
      else
        throw new Storage\Exception('Can`t create property of undefined type');
      
     // create property object
      $record['object'] = new $class([
        'storage' => $storage,
        'value' => $value
      ]);
      $record['time'] = time(); // может быть другой формат
      return $record;
    }
 }
 
 Storage\FS\Storage::factory([Storage\FS\Storage::DEFAULT_FACTORY, 'float', 'int', 'bool', 'string', 'str'], Property::class);