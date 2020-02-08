<?php
 
 namespace glx\HTTP\Server\I;
 
 use glx\HTTP;
 
 interface Request extends HTTP\I\Request
 {
    public function get(string $name = NULL);
    public function post(string $name = NULL);
    public function cookie(string $name = NULL);
    public function server(string $name = NULL);
    public function input(string $name = NULL);
    public function client(): Client;
    public function secure(): bool;
 }