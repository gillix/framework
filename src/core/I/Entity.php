<?php
    
    namespace glx\core\I;
    
    use glx\Common;

    interface Entity extends Common\I\Entity
    {
        public function id(): ID;
    }
 