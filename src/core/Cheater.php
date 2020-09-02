<?php
    
    namespace glx\core;
    
    // костыль в связи с корявостью PHP
    abstract class Cheater
    {
        abstract protected function _cheat(I\Joint $joint = null);
    }