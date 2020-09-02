<?php
    
    namespace glx\Storage\FS;
    
    use glx\core;
    use SplFileInfo;

    abstract class Factory implements I\Factory
    {
        public static function check(array $record, I\Storage $storage): void
        {
            if (!$record['source']) {
                return;
            }
            if (static::fileChanged($storage->structure()->source->path($record['source']), $record['time'])) {
                static::clear($record, $storage);
                throw new Factory\RecordChanged(static::recreate($record, $storage));
            }
        }
        
        public static function clear(array $record, I\Storage $storage): void
        {
            $storage->forget($record['object']->id()->object());
        }
        
        public static function purge(array $record, I\Storage $storage): void
        {
            static::clear($record, $storage);
        }
        
        public static function recreate(array $record, I\Storage $storage): array
        {
            $source = $record['source'];
            $current = $storage->structure()->source->get(implode('/', explode('/', $source, -1)));
            $file = str_replace($current->relative() . '/', '', $source);
            $info = self::fetchName($file);
            $info['file'] = $file;
            $info['path'] = $current->path($file);
            $info['old'] = $record['object'];
            
            return static::create($info, $current, $storage);
        }
        
        protected static function fileChanged($path, $time): bool
        {
            if (!is_dir($path) && !is_file($path)) {
                return true;
            }
            
            return (new SplFileInfo($path))->getMTime() > $time;
        }
        
        protected static function fetchName(string $name): array
        {
            [$name, $extension] = explode('.', $name, 2);
            [$name, $vis] = explode('#', $name, 2);
            $visibility = core\VisibilityLevels::convert($vis) ?? core\I\Visibility::PUBLIC;
            
            return ['name' => $name, 'extension' => $extension, 'visibility' => $visibility];
        }
        
    }
 