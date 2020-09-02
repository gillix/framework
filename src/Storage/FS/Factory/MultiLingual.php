<?php
    
    namespace glx\Storage\FS\Factory;
    
    use glx\core;
    use glx\Storage;

    class MultiLingual extends Property
    {
        
        public static function probe(array $info, Storage\FS\I\Structure $current): bool
        {
            $val = $info['content'];
            $extension = $info['extension'];
            if ($extension && in_array($extension, ['ml', 'mlstring'])) {
                return true;
            }
            
            return false;
        }
        
        public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
        {
            $record['creator'] = self::class;
            
            $value = null;
            // loading value of property
            if ($info['content']) {
                // if creates from parent .node definition
                $value = $info['content'];
                $record['source'] = $info['source'];
            } elseif ($info['file'] && is_file($path = $info['path'])) {
                // if creates from file
                $value = file_get_contents($path);
                $record['source'] = $current->relative($info['file']);
            }
            
            // create ml object
            $record['object'] = new core\MultiLingual([
             'storage' => $storage,
             'value'   => $value ?? []
            ]);
            $record['time'] = time(); // может быть другой формат
            
            return $record;
        }
    }
    
    Storage\FS\Storage::factory(['ml', 'mlstring'], MultiLingual::class);