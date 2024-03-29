<?php
    
    namespace glx\Session\ID;
    
    use glx\Context;
    use glx\Exception;
    use glx\HTTP;

    class Cookie extends Provider
    {
        protected string        $key;
        protected HTTP\I\Cookie $cookie;
        protected array         $options;
        
        public const DEFAULT_KEY = 'GLX-SESSION';
        
        public function __construct(string $key = null, HTTP\I\Cookie $cookie = null, array $options = [])
        {
            if (!$cookie && !($http = Context::http())) {
                throw new Exception('Can`t resolve session ID by cookie in non-http context');
            }
            $this->cookie = $cookie ?? $http->cookie();
            $this->options = $options;
            $this->key = $key ?? self::DEFAULT_KEY;
        }
        
        public function id(): string
        {
            if (!isset($this->id)) {
                $this->id = $this->cookie->get($this->key);
            }
            
            return $this->id;
        }
        
        public function exist(): bool
        {
            return (isset($this->id) && $this->id !== null) || $this->cookie->has($this->key);
        }
        
        public function create(int $lifetime = 0, array $options = []): string
        {
            $path = $options['path'] ?? $this->options['path'] ?? '/';
            $domain = $options['domain'] ?? $this->options['domain'] ?? null;
            $secure = $options['secure'] ?? $this->options['secure'] ?? true;
            $httponly = $options['httponly'] ?? $this->options['httponly'] ?? true;
            $samesite = $options['samesite'] ?? $this->options['samesite'] ?? ($secure ? 'none' : 'lax');

            $this->id = $this->generate();
            $this->cookie->set(
             $this->key,
             $this->id,
             $lifetime,
             $path,
             $domain,
             $secure,
             $httponly,
             $samesite
            );
            
            return $this->id;
        }
        
        public function delete(array $options = []): void
        {
            unset($this->id);
            $domain = $options['domain'] ?? $this->options['domain'] ?? null;
            $path = $options['path'] ?? $this->options['path'] ?? '/';
            $this->cookie->set($this->key, false, 0, $path, $domain);
        }
    }
