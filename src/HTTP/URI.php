<?php
    
    namespace glx\HTTP;
    
    
    class URI implements I\URI
    {
        protected array        $parts = [];
        protected static array $ports = [
         'http'   => 80,
         'https'  => 443,
         'ftp'    => 21,
         'gopher' => 70,
         'nntp'   => 119,
         'news'   => 119,
         'telnet' => 23,
         'tn3270' => 23,
         'imap'   => 143,
         'pop'    => 110,
         'ldap'   => 389,
        ];
        
        public function __construct($uri = null)
        {
            if (is_string($uri)) {
                $uri = parse_url($uri);
            } elseif ($uri instanceof I\URI) {
                $uri = $uri->parts();
            }
            if (is_array($uri)) {
                $this->parts = $uri;
            }
            $this->query($this->parts['query'] ?? '');
        }
        
        public function port(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function scheme(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function host(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function path(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function query($value = null): I\Query
        {
            if ($value !== null) {
                $this->parts['query'] = new Query($value);
            }
            
            return $this->parts['query'];
        }
        
        public function fragment(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function user(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function pass(string $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function parts(array $value = null): array
        {
            return $this->parts;
        }
        
        public function get(string $name)
        {
            return $this->parts[$name];
        }
        
        public function has(string $name): bool
        {
            return isset($this->parts[$name]);
        }
        
        protected function param(string $name, $value = null)
        {
            if ($value) {
                $this->parts[$name] = $value;
            }
            
            return $this->parts[$name];
        }
        
        public function with(array $params = []): I\URI
        {
            $new = new static($this);
            foreach ($params as $name => $value) {
                $new->param($name, $value);
            }
            
            return $new;
        }
        
        public function __toString()
        {
            extract($this->parts, null);
            /** @var string $scheme */
            /** @var string $port */
            $port = ($port && (int)$port !== self::$ports[$scheme]) ? ":{$port}" : null;
            $scheme = $scheme ? "{$scheme}://" : '//';
            /** @var string $pass */
            $pass = $pass ? ":{$pass}" : null;
            /** @var string $user */
            $user = $user ? "{$user}{$pass}@" : null;
            /** @var string $host */
            $host = $host ? "{$scheme}{$user}{$host}{$port}" : null;
            /** @var I\Query $query */
            $query = $query->count() ? "?{$query}" : null;
            /** @var string $fragment */
            $fragment = $fragment ? "#{$fragment}" : null;
            /** @var string $path */
            $path ??= '/';
            
            return "{$host}{$path}{$query}{$fragment}";
        }
        
    }
