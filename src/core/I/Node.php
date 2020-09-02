<?php
    
    namespace glx\core\I;
    
    use ArrayAccess;
    use glx\Common\I\ObjectAccess;
    use glx\Events;

    interface Node extends Entity, Joint, Inheritor, Ancestor, Events\I\Emitter, Caller, Rewriter, ObjectAccess, ArrayAccess
    {
        public function metatype(): array;
        
        public function parentOf(Joint $entity): bool;
        
        public function add($name, $item, int $visibility = Visibility::PUBLIC);
        
        public function remove(string $name, $type = null);
        
        public function has(string $name, $type = null): bool;
        
        public function property(string $name, $type = null): ?Joint;
        
        public function get(string $name, $type = null): ?Joint;
        
        public function select($condition = null): Selection;
        
        /**
         * @param string $name
         * @return Node|Invokable|Property|Image|MultiLingual
         */
        public function __get($name);
    }