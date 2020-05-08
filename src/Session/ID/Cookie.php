<?php
 
 namespace glx\Session\ID;
 
 use glx\Context;
 use glx\Exception;
 use glx\HTTP;
 
 class Cookie extends Provider
 {
    protected string $key;
    protected HTTP\I\Cookie $cookie;
    protected array $options;
    
    public const DEFAULT_KEY = 'GLX-SESSION';
  
    public function __construct(string $key = NULL, HTTP\I\Cookie $cookie = NULL, array $options = [])
    {
      if(!$cookie && !($http = Context::http()))
          throw new Exception('Can`t resolve session ID by cookie in non-http context');
      $this->cookie = $cookie ?? $http->cookie();
      $this->options = $options;
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
      $this->cookie->set(
          $this->key,
          $this->id,
          $lifetime,
          $this->options['path'] ?? '/',
          $this->options['domain'] ?? null,
          $this->options['secure'] ?? true,
          $this->options['httponly'] ?? true,
          $this->options['samesite'] ?? 'none'
      );
      return $this->id;
    }
  
    public function delete(): void
    {
      unset($this->id);
      $this->cookie->set($this->key, false);
    }
 }