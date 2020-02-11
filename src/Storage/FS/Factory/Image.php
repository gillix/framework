<?php
 namespace glx\Storage\FS\Factory;

 use glx\Storage;
 use glx\core;

 class Image extends File
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
        $props = [];
        if(($imageinfo = getimagesize($file = $storage->structure()->get('source')->path($source))) === false)
          throw new Storage\Exception('File is not detected as image file: '.$file);
        else
         {
          [$props['width'], $props['height'], $props['type'], $props['attributes']] = $imageinfo;
          $props['type'] = image_type_to_extension($props['type']);
         }
        $record['source'] = $source;
        $source = "/{$source}";
       }

      if($source === NULL)
        throw new Storage\Exception('Image source is not found');

      if($record['source'])
        // save to public section of compiler
        $storage->compiler()->copy($record['source'], 'source', 'public');
     
      // fetch options for new object
      $options = [
        'storage' => $storage,
        'source' => $source,
      ];
      if(isset($props))
        $options['info'] = $props;
      if(($old = $info['old']) && $old instanceof core\I\Entity)
        $options['id'] = $old->id();
      
     // create file object
      $object = new core\Image($options);
      $record['object'] = $object;
      $record['time'] = time(); // может быть другой формат
      return $record;
    }
  
 }
 
 Storage\FS\Storage::factory([Storage\FS\Storage::DEFAULT_FACTORY, 'image', 'img', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'ico'], Image::class);