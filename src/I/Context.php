<?php
    
    namespace glx\I;
    
    use glx\Cache;
    use glx\Common;
    use glx\Context\I\CallStack;
    use glx\Context\I\Profile;
    use glx\HTTP;
    use glx\Log;

    interface Context
    {
        public function callstack(): CallStack;
        
        public function persistent(): Cache\I\Persistent;
        
        public function temporary(string $name = null);
        
        public function input(string $name = null);
        
        public function http(): HTTP\I\Server;
        
        public function log(string $channel = null): Log\I\Channel;
        
        public function locale(Locale $locale = null): Locale;
        
        public function profile($profile = null): Profile;
        
        public function config(): Common\I\Collection;
        
        public function event(string $name = null);
    }
 