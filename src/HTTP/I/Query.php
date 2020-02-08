<?php
 
 namespace glx\HTTP\I;
 
 
 use glx\Common;

 interface Query extends Common\I\ObjectAccess
 {
    public function __toString();
 }