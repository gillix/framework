<?php
    
    namespace glx\HTTP;
    
    use glx\Common;

    class Cookie extends Common\Collection implements I\Cookie
    {
        protected array $cookies = [];
        
        public function delete($name, ?string $domain = null, ?string $path = null): void
        {
            $this->set($name, false, 0, $path, $domain);
        }
        
        public function has(string $name): bool
        {
            return $this->__isset($name);
        }
        
        public function set($name, $value, $lifetime = null, ?string $path = null, ?string $domain = null, bool $secure = null, bool $httponly = null, string $samesite = null): void
        {
            if (is_array($name)) {
                foreach ($name as $n => $v) {
                    $this->set($n, $v);
                }
                
                return;
            }
            if (is_array($lifetime)) {
                $options = $lifetime;
            } else {
                if ($lifetime) {
                    $options['expires'] = time() + $lifetime;
                }
                if ($path !== null) {
                    $options['path'] = $path;
                }
                if ($domain !== null) {
                    $options['domain'] = $domain;
                }
                if ($secure !== null) {
                    $options['secure'] = $secure;
                }
                if ($httponly !== null) {
                    $options['httponly'] = $httponly;
                }
                if ($samesite !== null) {
                    $options['samesite'] = $samesite;
                }
            }
            $options ??= [];
            $options['value'] = $value;
            $this->cookies[$name] = $options;
        }
        
        public function apply(): void
        {
            foreach ($this->cookies as $name => $options) {
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
