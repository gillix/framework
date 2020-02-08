<?php
 
 namespace glx\I;
 
 use glx\Cache;
 use glx\Common;
 use glx\Context\I\CallStack;
 use glx\Context\I\Profile;
 use glx\HTTP;
 use glx\Log;

 interface Context
 {
    public function callstack(): CallStack;
    public function persistent(): Cache\I\Persistent;
    public function temporary(string $name = NULL);
    public function input(string $name = NULL);
    public function http(): ?HTTP\I\Server;
    public function log(string $channel = NULL): Log\I\Channel;
    public function locale(\glx\I\Locale $locale = NULL): \glx\I\Locale;
    public function profile($profile = null): Profile;
    public function config(): ?Common\I\ObjectAccess;
    public function event(string $name = NULL);
 }
 