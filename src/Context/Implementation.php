<?php

 namespace glx\Context;
 
 use glx\Cache;
 use glx\core;
 use glx\Common;
 use glx\Error;
 use glx\HTTP;
 use glx\Locale;
 use glx\Log;
 use glx\Logger;
 use glx\Events;

 require_once 'CallStack.php';
 require_once __DIR__.'/../Cache/Persistent.php';
 require_once __DIR__.'/../Common/Collection.php';
// require_once __DIR__.'/../Config/ReadOnly.php';
 
 class Implementation implements \glx\I\Context
 {
    private I\CallStack $callstack;
    private Cache\I\Persistent $persistent;
    private Common\I\Collection $temporary;
    private \glx\I\Locale $locale;
    private \glx\I\Logger $logger;
    private Common\I\Collection $input;
    private Common\I\Collection $config;
    private HTTP\I\Server $http;
    private array $options;
    private I\Profile $profile;
    private Events\Manager $events;
    
 
    public function __construct(array $options = [])
    {
//      $this->options = $options;
      $this->callstack = new CallStack();
      $this->persistent = new Cache\Persistent($options['cache'] ?? []);
      $this->temporary = new Cache\Temporary();
      $this->events = new Events\Manager();
      $this->input = new Common\Collection($content = []);
      if($options['input'] && $options['input'] instanceof Common\I\Collection)
        $this->input->link($options['input']);
      if($options['config'] && $options['config'] instanceof Common\I\Collection)
        $this->config = $options['config'];
      $this->profile = new Profile($options['profile']);
      if($options['http'] && $options['http'] instanceof HTTP\I\Server)
        $this->http = $options['http'];
      if($options['locale'])
        $this->locale = $options['locale'] instanceof \glx\I\Locale ? $options['locale'] : Locale::get($options['locale']);
      else
        $this->locale = Locale::get('en_US');
      if($options['logger'])
        $this->logger = $options['logger'] instanceof \glx\I\Logger ? $options['logger'] : new Logger($options['logger']);
    }
   
    public function callstack(): I\CallStack
    {
      return $this->callstack;
    }
    
    public function persistent(): Cache\I\Persistent
    {
      return $this->persistent;
    }
 
    public function temporary(string $name = NULL)
    {
      if($name)
        return $this->temporary[$name];
      return $this->temporary;
    }
 
    public function input(string $name = NULL)
    {
      if($name)
        return $this->input[$name];
      return $this->input;
    }
 
    public function profile($profile = null): I\Profile
    {
      if($profile) $this->profile->set($profile);
      return $this->profile;
    }
 
    public function locale(\glx\I\Locale $locale = NULL): \glx\I\Locale
    {
      if($locale) $this->locale = $locale;
      return $this->locale;
    }
  
    public function http(): HTTP\I\Server
    {
      if(!isset($this->http))
        throw new Error('HTTP server parameters is not available in non-HTTP context');
      return $this->http;
    }
  
    public function log(string $channel = NULL): Log\I\Channel
    {
      if($channel)
        return $this->logger->to($channel);
      return $this->logger;
    }
   
    public function config(): Common\I\Collection
    {
      return $this->config ?? new Common\ReadOnly($empty = []);
    }
 
    public function event(string $name = NULL)
    {
      if($name)
        return new Events\Event($this->events, $name);
      return $this->events;
    }

    public function with($options, \Closure $function): self
    {
      // TODO: создать копию себя с измененными свойствами согласно опций
      // TODO: установить в стек, вызвать колбек, убрать из стека, вернуть результат колбека
    }
 }
 
