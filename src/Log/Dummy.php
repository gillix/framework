<?php
    
    namespace glx\Log;
    
    class Dummy implements I\Channel
    {
        public function debug(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function info(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function notice(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function warning(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function error(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function critical(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function alert(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function emergency(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function log(...$arguments): I\Channel
        {
            return $this;
        }
        
        public function name(): string
        {
            return '';
        }
    }