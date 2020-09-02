<?php
    
    namespace glx\core;
    
    
    class Boolean extends Property
    {
        protected static string $_type = 'BOOL';
        
        public function __construct($options)
        {
            if (is_bool($options)) {
                $this->value = $options;
            }
            parent::__construct($options);
        }
        
        public static function new(...$arguments): I\Property
        {
            return new static(...$arguments);
        }
        
        public static function resolve($value): ?I\Property
        {
            if (is_int($value)) {
                return self::new($value);
            }
            
            return null;
        }
        
        public function equals($other): bool
        {
            return $this->value === $other;
        }
    }
    
    // register class as value resolver
    Unit::resolver(Boolean::class);
    
    
    /**
     * global function for simplify usage
     * creates new object of Boolean
     * @param mixed ...$arguments
     * @return I\Property
     */
    function bool(...$arguments): I\Property
    {
        return Boolean::new(...$arguments);
    }
 
