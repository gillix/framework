<?php
    
    namespace glx\Storage\I;
    
    use glx;
    use glx\core;

    interface Storage
    {
        public function id(): string;
        
        public function register(core\I\Entity $object): core\I\ID;
        
        public function load(string $id): core\I\Entity;
    
        /**
         * Locate external storage
         * @param string|array $label
         * @param array|null $options
         * @return Storage
         */
        public function locate($label, array $options = null): Storage;
        
        public function root(): core\I\Entity;
        
        public static function new(array $options, glx\I\Context $context = null): Storage;
//    public function store();
//    public function export();
//    public function import();
    }