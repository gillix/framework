<?php
    
    namespace glx\Storage\Dynamic;
    
    use glx\core;
    use glx\Storage\Exception;
    use glx\Storage\I;
    use glx\Storage\Manager;

    include_once __DIR__ . '/../Storage.php';
    
    class Storage extends \glx\Storage\Storage
    {
        protected static I\Storage $instance;
        
        public function __construct(array $options = [])
        {
            parent::__construct(null, $options);
            if ($options['label']) {
                Manager::register($options['label'], $this);
            }
        }
        
        public static function get(): I\Storage
        {
            return self::$instance ?? (self::$instance = new self());
        }
        
        public function register($object): core\I\ID
        {
            $id = uniqid('', true);
            $this->registry->add($id, $object);
            
            return new core\ID('dynamic', $id);
        }
        
        public function root(): core\I\Entity
        {
            throw new Exception('This call has no sense in dynamic mode');
        }
        
        public function load(string $id): core\I\Entity
        {
            return $this->registry->object($id);
        }
        
    }