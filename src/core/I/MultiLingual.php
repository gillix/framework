<?php
    
    namespace glx\core\I;
    
    use glx\Common\_string;

    interface MultiLingual
    {
        public function get(string $lang = null): _string;
    }