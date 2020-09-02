<?php
    
    namespace glx\Config;
    
    use glx\Exception;
    use SplFileInfo;

    require_once 'I/Reader.php';

// TODO: переделать интерфейс: непрозрачно и неудобно
    class Reader implements I\Reader
    {
        protected static string $default = 'gcml';
        protected string        $parser;
        
        public function __construct(string $format = null)
        {
            if ($format) {
                $format = strtolower($format);
                if (!self::valid($format)) {
                    $format = null;
                }
            }
            if ($format === null) {
                $format = self::$default;
            }
            $this->parser = self::class($format);
        }
        
        public function parse(string $content): array
        {
            return $this->parser::parse($content, [
             'include' => fn($value) => array_merge(...array_map(fn($item) => static::read($item), (array)$value))
            ]);
        }
        
        public static function get(string $format = null): I\Reader
        {
            return new static($format);
        }
        
        protected static function valid(string $format): bool
        {
            // depends autoloader to be used
            return class_exists(self::class($format));
        }
        
        protected static function class(string $format): string
        {
            return "\glx\Config\\{$format}\Parser";
        }
        
        public static function default(string $format = null): string
        {
            if ($format) {
                $format = strtolower($format);
                if (self::valid($format)) {
                    self::$default = $format;
                }
            }
            
            return self::$default;
        }
        
        public static function read(string $path): array
        {
            if (!is_file($path)) {
                throw new Exception("Can`t read config file: '{$path}' is not exist.");
            }
            $cwd = getcwd();
            chdir(dirname($path));
            try {
                $format = (new SplFileInfo($path))->getExtension();
                
                return static::get($format)->parse(file_get_contents($path));
            }
            finally {
                chdir($cwd);
            }
        }
    }