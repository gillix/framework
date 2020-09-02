<?php
    
    namespace glx\Session;
    
    
    use glx\Common;
    use glx\Exception;

    class Session extends Common\Collection implements I\Session
    {
        protected ID\I\Provider $id;
        protected I\Storage     $storage;
        protected bool          $started  = false;
        protected bool          $unsaved  = false;
        protected int           $lifetime = 0;
        protected const DEFAULT_STORAGE = 'cache';
        
        public function __construct(ID\I\Provider $id, array $options = [])
        {
            $this->id = $id;
            $storage = $options['storage'] ?? self::DEFAULT_STORAGE;
            if ($options['lifetime']) {
                $this->lifetime = $options['lifetime'];
            }
            if ($storage instanceof I\Storage) {
                $this->storage = $storage;
            } elseif (is_string($storage) && is_file($file = __DIR__ . "/{$storage}/Storage.php")) {
                include_once $file;
                if (class_exists($class = "\glx\Session\\{$storage}\Storage")) {
                    $this->storage = new $class($options);
                }
            }
            if (!isset($this->storage)) {
                throw new Exception('Session storage not configured properly.');
            }
            parent::__construct($a = []);
        }
        
        public function get(string $name, $default = null)
        {
            $this->load();
            
            return $this->content[$name] ?? $default;
        }
        
        public function set(string $name, $value): void
        {
            $this->load() || $this->create();
            $this->content[$name] = $value;
            $this->unsaved = true;
        }
        
        public function destroy(): void
        {
            $this->storage->delete((string)$this->id);
            $this->id->delete();
            $this->started = false;
        }
        
        public function __get($name)
        {
            return $this->get($name);
        }
        
        public function __set($name, $value)
        {
            $this->set($name, $value);
        }
        
        public function __isset($name): bool
        {
            return $this->has($name);
        }
        
        public function __unset($name)
        {
            $this->forget($name);
        }
        
        public function has(string $name): bool
        {
            return $this->id->exist() && $this->load() && $this->started && isset($this->content[$name]);
        }
        
        public function started(): bool
        {
            return $this->started;
        }
        
        public function purge(): void
        {
            $this->load();
            $this->content = [];
        }
        
        public function forget(string $name): void
        {
            $this->load();
            unset($this->content[$name]);
        }
        
        public function refresh(): void
        {
            $this->storage->relocate((string)$this->id, $this->id->create($this->lifetime));
        }
        
        protected function load(): bool
        {
            
            if (!$this->started && $this->id->exist()) {
                $this->content = $this->storage->read((string)$this->id) ?? [];
                $this->started = true;
            }
            
            return $this->started;
        }
        
        public function create(int $lifetime = 0, array $options = []): bool
        {
            if ($this->started) {
                $this->destroy();
            }
            
            return $this->started = (bool)$this->id->create($lifetime ?? $this->lifetime, $options);
        }
        
        protected function save(): void
        {
            $this->storage->write((string)$this->id, $this->content, $this->lifetime);
            $this->unsaved = false;
        }
        
        protected function close(): void
        {
            if ($this->started) {
                if ($this->unsaved) {
                    $this->save();
                }
                $this->started = false;
            }
        }
        
        public function __destruct()
        {
            $this->close();
        }
    }