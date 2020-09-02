<?php
    
    namespace glx\HTTP\I;
    
    use glx\HTTP\Server\I\Request;
    use glx\HTTP\Server\I\Response;
    use glx\Session\I\Session;

    interface Server
    {
        public function cookie(): Cookie;
        
        public function request(): Request;
        
        public function response(): Response;
        
        public function session(string $channel = null): Session;
        
        public function send(): void;
    }