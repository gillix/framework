<?php
 
 namespace glx\HTTP\I;
 
 use glx\HTTP\Server\I\Request;
 use glx\HTTP\Server\I\Response;
 
 interface Server
 {
    public function cookie(): Cookie;
    public function request(): Request;
    public function response(): Response;
    public function send(): void;
 }