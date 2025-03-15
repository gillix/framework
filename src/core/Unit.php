<?php
    
    namespace glx\core;
    
    use glx\Context;
    use glx\Events;

    abstract class Unit extends Entity implements I\Joint, Events\I\Support
    {
        use Events\Delegated;
        
        // костыль в связи с корявостью PHP
        private I\Joint|null $_joint    = null;
        private static array $resolvers = [];
        
        protected function _cheat(I\Joint|null $joint = null)
        {
            if ($joint) {
                $this->_joint = $joint;
            } /*else unset($this->_joint);*/
        }
        
        // Joint interface fake implementstion
        public function name(): string { return isset($this->_joint) ? $this->_joint->name() : '/'; }
        
        public function parent(): ?I\Joint { return isset($this->_joint) ? $this->_joint->parent() : null; }
        
        public function owner(): ?I\Joint { return isset($this->_joint) ? $this->_joint->owner() : null; }
        
        public function closest($type): I\Joint|null
        {
            if ($this instanceof I\Node && $this->is($type)) {
                return $this;
            }
            
            return null;
        }
        
        public function location(): string
        {
            // TODO: должен включать идентификатор хранилища/пакета
            return isset($this->_joint) ? $this->_joint->location() : '/';
        }
        
        public function path(array|null $options = null): string
        {
            return isset($this->_joint) ? $this->_joint->path($options) : '/' .
             ((($options === null || $options['clean'] !== true) && $this instanceof I\Rewriter) ? $this->extend($options) : null);
        }
        
        public function childOf(I\Joint $parent): bool { return isset($this->_joint) && $this->_joint->childOf($parent); }
        
        public function origin(): I\Entity { return $this; }
        
        public function root(): I\Joint { return $this; }
        
        public function visibility(): int { return I\Visibility::PUBLIC; }
        
        public function profile(): string { return Context::DEFAULT_PROFILE; }
        
        protected function this(): I\Joint { return $this->_joint ?? $this; }
        
        public function __toString()
        {
            return "[{$this->type()}:{$this->location()}]";
        }
        
        public static function resolver($class)
        {
            if (class_exists($class, false) && method_exists($class, 'resolve')) {
                self::$resolvers[] = $class;
            }
        }
        
        public static function resolve($value)
        {
            if ($value instanceof I\Entity || $value instanceof I\Binder) {
                return $value;
            }
            foreach (self::$resolvers as $resolver) {
                if ($resolved = $resolver::resolve($value)) {
                    return $resolved;
                }
            }
            
            return null;
        }
    }
