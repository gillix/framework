<?php
    
    namespace glx\HTTP;
    
    
    use const EXTR_IF_EXISTS;

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
        
        public function port(string|null $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function scheme(string|null $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function host(string|null $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function path(string|null $value = null): string
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
        
        public function fragment(string|null $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function user(string|null $value = null): string
        {
            return $this->param(__FUNCTION__, $value);
        }
        
        public function pass(string|null $value = null): string
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
                if ($name === 'query') {
                    $this->query($value);
                } else {
                    $new->param($name, $value);
                }
            }
            
            return $new;
        }
        
        public function __toString()
        {
            $port = ($this->has('port') && (int)$this->port() !== self::$ports[$this->scheme()]) ? ":{$this->port()}" : null;
            $scheme = $this->has('scheme') ? "{$this->scheme()}://" : '//';
            $pass = $this->has('pass') ? ":{$this->pass()}" : null;
            $user = $this->has('user') ? "{$this->user()}{$pass}@" : null;
            $host = $this->has('host') ? "{$scheme}{$user}{$this->host()}{$port}" : null;
            $query = ($this->has('query') && $this->query()->count()) ? "?{$this->query()}" : null;
            $fragment = $this->has('fragment') ? "#{$this->fragment()}" : null;
            $path = $this->path() ?? '/';
            
            return "{$host}{$path}{$query}{$fragment}";
        }
        
    }
