<?php
 
 namespace glx\HTTP;
 
 use glx\Common;

 class Cookie extends Common\Collection implements I\Cookie
 {
    protected array $cookies = [];
  
    public function delete($name): void
    {
      $this->set($name, false);
    }
   
    public function has(string $name): bool
    {
      return $this->__isset($name);
    }
   
    public function set($name, $value, $lifetime = NULL, string $path = NULL, string $domain = NULL, bool $secure = NULL, bool $httponly = NULL): void
    {
      if(is_array($name))
       {
        foreach($name as $n => $v)
          $this->set($n, $v);
        return;
       }
      if(is_array($lifetime))
        $options = $lifetime;
      else
       {
        if($lifetime)
          $options['expires'] = time() + $lifetime;
        if($path !== NULL)
          $options['path'] = $path;
        if($domain !== NULL)
          $options['domain'] = $domain;
        if($secure !== NULL)
          $options['secure'] = $secure;
        if($httponly !== NULL)
          $options['httponly'] = $httponly;
       }
      $options ??= [];
      $options['value'] = $value;
      $this->cookies[$name] = $options;
    }
   
    public function apply(): void
    {
      foreach($this->cookies as $name => $options)
       {
        $value = $options['value'];
        unset($options['value']);
        setcookie($name, $value, $options);
       }
    }
 
    public function get(string $name)
    {
      return $this->__get($name);
    }
 }