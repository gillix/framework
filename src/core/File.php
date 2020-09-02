<?php
    
    namespace glx\core;
    
    use glx\HTTP;

    class File extends Unit implements I\File
    {
        protected static string $_type = 'FILE';
        protected HTTP\I\URI    $uri;
        protected               $source;
        
        public function __construct($options = null)
        {
            if (is_string($options)) {
                $this->source = $options;
            } elseif (is_array($options)) {
                if ($options['source']) {
                    $this->source = $options['source'];
                }
            }
            parent::__construct(is_array($options) ? $options : []);
        }
        
        public function source(): string
        {
            return $this->source;
        }
        
        public function uri(): HTTP\I\URI
        {
            if (!isset($this->uri)) {
                $this->uri = new HTTP\URI($this->source());
            }
            
            return $this->uri;
        }
        
        public function url(): string
        {
            return $this->uri();
        }
        
        public static function new(...$arguments): I\File
        {
            return new static(...$arguments);
        }
        
        public static function resolve($value): ?I\File
        {
            if (is_string($value) && is_file($value)) {
                return self::new($value);
            }
            
            return null;
        }
    }
    
    // register class as value resolver
    Unit::resolver(File::class);
    
    
    /**
     * global function for simplify usage
     * creates new object of File
     * @param mixed ...$arguments
     * @return I\File
     */
    function file(...$arguments): I\File
    {
        return File::new(...$arguments);
    }
