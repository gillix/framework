<?php
    
    namespace glx\Storage\FS\Factory;
    
    use glx\Config;
    use glx\core;
    use glx\Storage;

    class Component extends Storage\FS\Factory
    {
        public static function probe(array $info, Storage\FS\I\Structure $current): bool
        {
            if (in_array($info['extension'], ['lib', 'component'])) {
                return true;
            }
            
            return false;
        }
        
        public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
        {
            $record['creator'] = self::class;
            
            if ($info['content']) // if creates from parent .node definition
            {
                $options = $info['content'];
            } elseif ($info['file'] && is_file($path = $info['path'])) {
                // if creates from file
                $record['source'] = $current->relative($info['file']);
                // TODO: добывать из конфига дефолтовый формат
                $options = Config\Reader::get()->parse(file_get_contents($path));
            }
            
            $options['storage'] = $storage;
            
            if (($old = $info['old']) && $old instanceof core\I\Entity) {
                $options['id'] = $old->id();
            }
            
            // create library object
            $record['object'] = new core\Component($options);
            $record['time'] = time(); // может быть другой формат
            
            return $record;
        }
    }
    
    Storage\FS\Storage::factory(['lib', 'component'], Component::class);