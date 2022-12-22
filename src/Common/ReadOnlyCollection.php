<?php
    
    namespace glx\Common;
    
    use glx\Exception;

    require_once 'Collection.php';
    
    class ReadOnlyCollection extends Collection
    {
        public function __construct(array $array)
        {
            parent::__construct($array);
        }

        public function __set($name, $value)
        {
            throw new Exception('Can`t modify read-only configuration');
        }
        
        public function __unset($name)
        {
            throw new Exception('Can`t modify read-only configuration');
        }
    }
