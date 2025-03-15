<?php
    
    namespace glx\I;
    
    use glx\Log;

    interface Logger extends Log\I\Channel
    {
        public function to(string $channel): Log\I\Channel;
        
        public static function new(string $channel, array|null $options = null): Log\I\Channel;
//   public function __call($name, $arguments);
    }
 
