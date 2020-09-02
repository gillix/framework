<?php
    
    namespace glx\HTTP\I;
    
    
    use glx\Common;

    interface Query extends Common\I\Collection
    {
        public function __toString();
    }