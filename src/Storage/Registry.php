<?php
    
    namespace glx\Storage;
    
    use glx\core;

    class Registry
    {
        protected array $records;
        
        public function __construct()
        {
            $this->records = [];
        }
        
        public function add(string $id, $value, string $section = 'object'): void
        {
            // allow overwriting
//      if(array_key_exists($id, $this->records))
//        throw new Storage\Exception('Trying to double register object'); //TODO: сделать класс исключения
            if (is_array($value)) {
                $this->records[$id] = $value;
            } else {
                $this->records[$id][$section] = $value;
            }
        }
        
        public function remove(string $id, string $section = null): void
        {
            if ($section) {
                unset($this->records[$id][$section]);
            } else {
                unset($this->records[$id]);
            }
        }
        
        public function object(string $id): ?core\I\Entity
        {
            return $this->records[$id]['object'];
        }
        
        public function record(string $id): ?array
        {
            return $this->records[$id] ?? null;
        }
        
        public function section(string $id, string $section)
        {
            return $this->records[$id][$section];
        }
    }
