<?php
    
    namespace glx\Session\ID;
    
    abstract class Provider implements I\Provider
    {
        protected string $id;
        
        protected function generate(): string
        {
            return md5(uniqid('session', true));
        }
        
        public function __toString()
        {
            return $this->id();
        }
    }