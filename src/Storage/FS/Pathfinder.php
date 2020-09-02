<?php
    
    namespace glx\Storage\FS;
    
    use glx\Storage\Manager;

    require_once __DIR__ . '/../Manager.php';
    
    class Pathfinder
    {
        private $storage;
        private $relative;
        private $section;
        
        public function __construct(string $id, string $relative, string $section)
        {
            $this->storage = $id;
            $this->relative = $relative;
            $this->section = $section;
        }
        
        public function __toString()
        {
            return (string)Manager::get($this->storage)->structure()->path($this->relative, $this->section);
        }
    }