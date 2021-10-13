<?php
    
    namespace glx\Cache;
    
    use glx\Common;

    class Temporary extends Common\Collection
    {
        public function __construct()
        {
            $empty = [];
            parent::__construct($empty);
        }
    }