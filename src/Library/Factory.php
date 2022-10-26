<?php
    
    namespace glx\Library;
    
    use Closure;
    use ReflectionClass;
    use ReflectionException;

    class Factory implements I\Factory
    {
        protected array        $instances;
        protected array        $makers;
        protected array        $parents;
        protected array        $sources;
        protected static array $_sources;
        protected static array $_makers;
        protected static array $_aliases;
        
        
        public function __construct(array $options = [])
        {
            if ($use = $options['use']) {
                $use = (array)$use;
                foreach ($use as $parent) {
                    $this->use($parent);
                }
            }
            if (($bind = $options['bind']) && is_array($bind)) {
                foreach ($bind as $id => $maker) {
                    if (is_object($maker)) {
                        $this->instance($id, $maker);
                    } elseif (is_string($maker) || $maker instanceof Closure) {
                        $this->bind($id, $maker/*['maker' => $maker]*/);
                    } elseif (is_array($maker)) {
                        if (!($class = $maker['class'] ?? $maker['factory'])) {
                            continue;
                        }
                        $this->bind($id, $class, $maker['arguments'], $maker['source']);
                        if ($alias = $maker['alias']) {
                            $this->alias($id, $alias);
                        }
                    }
                }
            }
        }
        
        public function get(string $id, $default = null)
        {
            $maker = $this->find($id);
            if (is_object($maker)) {
                return $maker;
            }
            if (is_array($maker)) {
                $this->include($id);
                if ($instance = $this->make($maker['maker'], $maker['arguments'])) {
                    $this->instance($id, $instance);
                    
                    return $instance;
                }
            } elseif ($default !== null) {
                return $this->make($default);
            }
            
            return null;
        }
        
        public function has(string $id): bool
        {
            $key = self::key($id);
            if (isset($this->instances) && array_key_exists($key, $this->instances)) {
                return true;
            }
            if (isset($this->makers) && array_key_exists($key, $this->makers)) {
                return true;
            }
            if (isset($this->parents)) {
                foreach ($this->parents as $parent) {
                    if ($result = $parent->has($id)) {
                        return $result;
                    }
                }
            }
            if (isset(self::$_makers) && array_key_exists($key, self::$_makers)) {
                return true;
            }
            
            return false;
        }
        
        public function new(string $id, $default = null, array $arguments = null)
        {
            if ($arguments === null && is_array($default)) {
                $arguments = $default;
                $default = null;
            }
            
            if (is_array($maker = $this->maker($id))) {
                $this->include($id);
                
                return $this->make($maker['maker'], $arguments ?? $maker['arguments']);
            } elseif ($default !== null) {
                return $this->make($default, $arguments ?? []);
            }
            
            return null;
        }
        
        protected function include($id): void
        {
            if ((isset($this->sources) && ($source = $this->sources[$id]) && is_file($source)) ||
             (isset(self::$_sources) && ($source = self::$_sources[$id]) && is_file($source))) {
                require_once $source;
            }
        }
        
        protected function find($id)
        {
            $key = self::key($id);
            if (isset($this->instances) && array_key_exists($key, $this->instances)) {
                return $this->instances[$key];
            }
            if (isset($this->makers) && array_key_exists($key, $this->makers)) {
                return $this->makers[$key];
            }
            if (isset($this->parents)) {
                foreach ($this->parents as $parent) {
                    if ($result = $parent->find($id)) {
                        return $result;
                    }
                }
            }
            if (isset(self::$_makers) && array_key_exists($key, self::$_makers)) {
                return self::$_makers[$key];
            }
            
            return null;
        }
        
        public function maker($id)
        {
            $key = self::key($id);
            if (isset($this->makers) && array_key_exists($key, $this->makers)) {
                return $this->makers[$key];
            }
            if (isset($this->parents)) {
                foreach ($this->parents as $parent) {
                    if ($result = $parent->maker($id)) {
                        return $result;
                    }
                }
            }
            if (isset(self::$_makers) && array_key_exists($key, self::$_makers)) {
                return self::$_makers[$key];
            }
            
            return null;
        }
        
        public function set($id, $maker, array $arguments = null, ?string $source = null): void
        {
            if (is_object($maker)) {
                $this->instance($id, $maker);
            } else {
                $this->bind($id, $maker, $arguments, $source);
            }
        }
        
        public function instance($id, $instance): void
        {
            $this->instances ??= [];
            $id = (array)$id;
            $key = self::key($id[0]) ?? uniqid('', true);
            self::$_aliases ??= [];
            foreach ($id as $i) {
                self::$_aliases[$i] = $key;
            }
            $this->instances[$key] = $instance;
        }
        
        public function bind($id, $maker, ?array $arguments = null, ?string $source = null): void
        {
            $this->makers ??= [];
            $maker = ['maker' => $maker];
            if ($arguments) {
                $maker['arguments'] = $arguments;
            }
            if ($source && is_string($maker['maker'])) {
                $this->sources ??= [];
                $this->sources[$maker['maker']] = $source;
            }
            $id = (array)$id;
            $key = self::key($id[0]) ?? uniqid('', true);
            self::$_aliases ??= [];
            foreach ($id as $i) {
                self::$_aliases[$i] = $key;
            }
            $this->makers[$key] = $maker;
        }
        
        public function alias(string $id, $alias): void
        {
            $alias = (array)$alias;
            $key = self::key($id);
            if ($key) {
                foreach ($alias as $i) {
                    self::$_aliases[$i] = $key;
                }
            }
        }
        
        public function use($other): void
        {
            $this->parents ??= [];
            if (is_array($other)) {
                foreach ($other as $parent) {
                    $this->parents[] = $parent;
                }
            } else {
                $this->parents[] = $other;
            }
        }
        
        public static function default($id, $maker, ?array $arguments = null, ?string $source = null): void
        {
            self::$_makers ??= [];
            $maker = ['maker' => $maker];
            if ($arguments) {
                $maker['arguments'] = $arguments;
            }
            if ($source && is_string($maker['maker'])) {
                self::$_sources ??= [];
                self::$_sources[$maker['maker']] = $source;
            }
            $id = (array)$id;
            $key = self::key($id[0]) ?? uniqid('', true);
            self::$_aliases ??= [];
            foreach ($id as $i) {
                self::$_aliases[$i] = $key;
            }
            self::$_makers[$key] = $maker;
        }
        
        public static function defaults(array $bind): void
        {
            foreach ($bind as $id => $maker) {
                if (is_string($maker)) {
                    $maker = ['class' => $maker];
                }
                static::default(array_filter([$id, $maker['alias'] ?? null]), $maker['class'] ?? $maker['factory'], $maker['arguments'] ?? null, $maker['source'] ?? null);
            }
        }
        
        protected static function key($id)
        {
            if (isset(self::$_aliases)) {
                return self::$_aliases[$id];
            }
            
            return null;
        }
        
        /**
         * Create object from a maker
         * Try to resolve arguments with literal reference
         * Try to resolve input parameters if arguments not provided
         * Inject factory object into the just created
         * @param $maker
         * @param array|null $arguments
         *
         * @return mixed|object
         * @throws FactoryResolutionException
         */
        protected function make($maker, ?array $arguments = null)
        {
            if ($maker instanceof Closure) {
                $object = $maker->call($this, $arguments);
                $class = get_class($object);
            } elseif (is_string($maker)) {
                if (!class_exists($maker)) {
                    $this->include($maker);
                }
                if (class_exists($maker)) {
                    $class = $maker;
                } else {
                    throw new FactoryResolutionException("Can`t resolve target class {$maker}.");
                }
            }
            if (!isset($class)) {
                throw new FactoryResolutionException('Can`t resolve target class.');
            }
            try {
                $reflector = new ReflectionClass($class);
            } catch (ReflectionException $e) {
                throw new FactoryResolutionException("Target class [$class] does not exist.", 0, $e);
            }
            $isConsumer = $reflector->implementsInterface(I\FactoryConsumer::class) || in_array('FactoryConsumer', $reflector->getTraitNames(), true);
            if (!isset($object)) {
                if ($arguments) {
                    foreach ($arguments as $i => $argument) {
                        if (is_string($argument) && strpos($argument, '=>') === 0) {
                            if ($instance = $this->get(trim(substr($argument, 2)))) {
                                $arguments[$i] = $instance;
                            }
                        }
                    }
                } elseif ($constructor = $reflector->getConstructor()) {
                    foreach ($constructor->getParameters() as $parameter) {
                        if ($parameter->getClass() !== null && ($instance = $this->get($parameter->getClass()->name))) {
                            $arguments[] = $instance;
                        } elseif ($parameter->isOptional()) {
                            try {
                                $arguments[] = $parameter->getDefaultValue();
                            } catch (ReflectionException $e) {
                            }
                        } else {
                            throw new FactoryResolutionException("Can`t resolve arguments for class {$class}.");
                        }
                    }
                }
                if ($isConsumer) {
                    $object = $reflector->newInstanceWithoutConstructor();
                    $object->factory($this);
                    if (isset($constructor) || ($constructor = $reflector->getConstructor())) {
                        $constructor->invokeArgs($object, $arguments ?? []);
                    }
                } else {
                    $object = $reflector->newInstanceArgs($arguments ?? []);
                }
            } elseif ($isConsumer) {
                $object->factory($this);
            }
            
            return $object;
        }
        
    }

