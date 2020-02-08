<?php
 
 namespace glx\HTTP\I;
 
 
 interface Launcher extends \glx\I\Launcher
 {
    public function server(): Server;
 }