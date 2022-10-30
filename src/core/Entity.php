<?php
    
    namespace glx\core;
    
    use glx\Storage;

    abstract class Entity extends Cheater implements I\Entity, I\Visibility
    {
        protected static string $_type = 'OBJECT';
        protected I\ID          $_id;
        
        public function __construct(array $options = [])
        {
            if (isset($options['id'])) {
                $this->_id = $options['id'];
            } else {
                $storage = $options['storage'] ?? Storage\Dynamic\Storage::get();
                $this->_id = $storage->register($this);
            }
        }
        
        public function id(): I\ID { return $this->_id; }
        
        public function type(): string { return static::$_type; }
        
        public function is(string $type, bool $not = false): bool
        {
            $current = get_class($this);
            $classes = class_parents($current);
            $classes[] = $current;
            
            foreach ($classes as $class) {
                if (isset($class::$_type) && strtoupper($type) === strtoupper($class::$_type)) {
                    return !$not;
                }
            }
            
            return $not;
        }
        
        public function not(string $type): bool
        {
            return $this->is($type, true);
        }
        
        public function sameAs($entity): bool
        {
            return (string)$this->id() === (string)$entity->id();
        }
    }
