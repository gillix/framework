<?php
 
 namespace glx\Session\ID;
 
 use glx\Context;
 use glx\Exception;
 use glx\HTTP;
 
 class Cookie implements I\Provider
 {
    protected string $key;
    protected string $id;
    protected HTTP\I\Cookie $cookie;
    
    public const DEFAULT_KEY = 'GILLIX-SESSION';
  
    public function __construct(string $key = NULL, HTTP\I\Cookie $cookie = NULL)
    {
      $this->cookie = $cookie ?? ($http = Context::http()) ? $http->cookie() : NULL;
      if(!$this->cookie)
        throw new Exception('Can`t resolve session ID by cookie in non-http context');
      $this->key = $key ?? self::DEFAULT_KEY;
    }
  
    public function id(): string
    {
      if(!isset($this->id))
        $this->id = $this->cookie->get($this->key);
      return $this->id;
    }

    public function exist(): bool
    {
      return (isset($this->id) && $this->id !== NULL) || $this->cookie->has($this->key);
    }
 
    public function create(int $lifetime = 0): string
    {
      $this->id = $this->generate();
      $this->cookie->set($this->key, $this->id, $lifetime, '/', NULL, true, true);
      return $this->id;
    }
  
    protected function generate(): string
    {
      return md5(uniqid('session', true));
    }
 
    public function delete(): void
    {
      unset($this->id);
      $this->cookie->set($this->key, false);
    }
 }