<?php
 
 namespace glx\Storage\FS\Factory;
 
 use glx\Storage;
 use glx\core;

 class File extends Storage\FS\Factory
 {

    public static function probe(array $info, Storage\FS\I\Structure $current): bool
    {
      if(($info['content'] && in_array($info['extension'], ['image', 'img'], true)) ||
         ($info['file'] && in_array(strtolower($info['extension']), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico'])))
        return true;
      return false;
    }
  
    public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
    {
      $record['creator'] = self::class;

      $source = NULL;
      if($info['content'])
       {
        // if creates from parent .node definition
        $source = $info['content'];
       }
      elseif($info['file'] && is_file($path = $info['path']))
       {
        // if creates from file
        $source = $current->relative($info['file']);
        $record['source'] = $source;
       }

      if($source === NULL)
        throw new Storage\Exception('File source is not found');

      // save to public section of compiler
      if($record['source'])
        $storage->compiler()->copy($record['source'], 'source', 'public');
     
      // fetch options for new object
      $options = [
        'storage' => $storage,
        'source' => $source
      ];
      if(($old = $info['old']) && $old instanceof core\I\Entity)
        $options['id'] = $old->id();
      
     // create file object
      $object = new core\File($options);
      $record['object'] = $object;
      $record['time'] = time(); // может быть другой формат
      return $record;
    }
  
    public static function clear(array $record, Storage\FS\I\Storage $storage): void
    {
      $storage->compiler()->delete($record['hidden'], 'hidden');
      parent::clear($record, $storage);
    }
 }
 
 Storage\FS\Storage::factory([Storage\FS\Storage::DEFAULT_FACTORY, 'image', 'img', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico'], File::class);