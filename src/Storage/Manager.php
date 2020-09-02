<?php
    
    namespace glx\Storage;
    
    
    use Closure;
    use DirectoryIterator;

    final class Manager implements I\Manager
    {
        private static array $storage   = [];
        private static array $loaders   = [];
        private static array $factories = [];
        public const STORAGE_ANY = '*';
        
        public static function get($label, array $options = null): ?I\Storage
        {
            
            // TODO: подумать о постоянном кешировании хранилищ
            
            if (is_array($label)) {
                if (!($type = $label['type'] ?? $label[0]) || !($label = $label['location'] ?? $label['path'] ?? $label[1])) {
                    return null;
                }
//        if(self::include() && ($factory = self::$factories[$type]))
//          return $factory::new($label, $options);
                if (self::include() && ($loaders = self::$loaders[$type])) {
                    foreach ($loaders as $loader) {
                        if ($storage = $loader($label, $options)) {
                            self::register($label, $storage);
                            
                            return $storage;
                        }
                    }
                }
            } elseif (is_string($label)) {
                if (strpos($label, ':')) {
                    return self::get(explode(':', $label, 2), $options);
                }
                if ($storage =& self::$storage[$label]) {
                    if (!($storage instanceof I\Storage)) {
                        $storage = self::get($storage, $options);
                    }
                    
                    return $storage;
                }
                if (self::include() && ($default = self::$loaders[self::STORAGE_ANY])) {
                    foreach ($default as $loader) {
                        if ($storage = $loader($label, $options)) {
                            self::register($label, $storage);
                            
                            return $storage;
                        }
                    }
                }
            }
            
            return null;
        }
        
        public static function register(string $label, $storage): void
        {
            self::$storage[$label] = $storage;
        }
        
        public static function autoloader(Closure $loader, $type = self::STORAGE_ANY): void
        {
            if (is_array($type)) {
                foreach ($type as $item) {
                    self::autoloader($loader, $item);
                }
            } else {
                self::$loaders[$type][] = $loader;
            }
        }
        
        public static function storage(string $type, string $factory): void
        {
            self::$factories[$type] = $factory;
        }
        
        private static function include(): bool
        {
            if (!count(self::$factories)) {
                foreach (new DirectoryIterator(__DIR__) as $factory) {
                    if (in_array($factory->getFilename(), ['.', '..']) || !$factory->isDir() || !is_file($include = $factory->getPathname() . DIRECTORY_SEPARATOR . 'Storage.php')) {
                        continue;
                    }
                    include_once $include;
                }
            }
            
            return (bool)count(self::$factories);
        }
    }
