<?php
    
    namespace glx\Config;
    
    use glx\Cache\Persistent;
    use glx\Exception;
    use SplFileInfo;

    require_once 'I/Reader.php';
    
    class CachedReader extends Reader
    {
        public static function read(string $path, array $cacheOptions = []): array
        {
            if (!is_file($path)) {
                throw new Exception("Can`t read config file: '{$path}' is not exist.");
            }
            $cache = new Persistent($cacheOptions);
            $key = md5($path);
            if (!($record = $cache->get($key)) || static::changed($path, $record['time'])) {
                $record ??= [];
                $record['content'] = parent::read($path);
                $record['time'] = time();
                $cache->store($key, $record);
            }
            
            return $record['content'];
        }
        
        protected static function changed(string $path, $time): bool
        {
            return (new SplFileInfo($path))->getMTime() > $time;
        }
    }