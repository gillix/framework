<?php
 
 namespace glx\Library;
 
 class Factory implements I\Factory
 {
    protected array $instances;
    protected array $makers;
    protected array $parents;
    protected array $sources;
    protected static array $_sources;
    protected static array $_makers;
    protected static array $_aliases;
    
  
    public function __construct(array $options = [])
    {
      if($use = $options['use'])
       {
        $use = (array)$use;
        foreach($use as $parent)
          $this->use($parent);
       }
      if(($bind = $options['bind']) && is_array($bind))
       {
        foreach($bind as $id => $maker)
          if(is_object($maker))
            $this->instance($id, $maker);
          elseif(is_string($maker) || $maker instanceof \Closure)
            $this->bind($id, $maker/*['maker' => $maker]*/);
          elseif(is_array($maker))
           {
            if(!($class = $maker['class'] ?? $maker['factory']))
              continue;
            $this->bind($id, $class, $maker['arguments'], $maker['source']);
            if($alias = $maker['alias'])
              $this->alias($id, $alias);
           }
       }
    }
  
    public function get(string $id, $default = NULL)
    {
      $maker = $this->find($id);
      if(is_object($maker))
        return $maker;
      if(is_array($maker))
       {
        $this->include($id);
        if($instance = $this->make($maker['maker'], $maker['arguments']))
         {
          $this->instance($id, $instance);
          return $instance;
         }
       }
      elseif($default !== NULL)
        return $this->make($default);
      return NULL;
    }
  
    public function has(string $id): bool
    {
      $id = self::key($id);
      if(isset($this->instances) && array_key_exists($id, $this->instances))
        return true;
      if(isset($this->makers) && array_key_exists($id, $this->makers))
        return true;
      if(isset($this->parents))
        foreach($this->parents as $parent)
          if($result = $parent->has($id))
            return $result;
      if(isset(self::$_makers) && array_key_exists($id, self::$_makers))
        return true;
      return false;
    }
  
    public function new(string $id, $default = NULL, array $arguments = NULL)
    {
      if($arguments === NULL && is_array($default))
       {
        $arguments = $default;
        $default = NULL;
       }
     
      if(is_array($maker = $this->maker($id)))
       {
        $this->include($id);
        return $this->make($maker['maker'], $arguments ?? $maker['arguments']);
       }
      elseif($default !== NULL)
        return $this->make($default, $arguments ?? []);
      return NULL;
    }

    protected function include($id): void
    {
      if((isset($this->sources) && ($source = $this->sources[$id]) && is_file($source)) ||
         (isset(self::$_sources) && ($source = self::$_sources[$id]) && is_file($source)))
        require_once $source;
    }
  
    protected function find($id)
    {
      $id = self::key($id);
      if(isset($this->instances) && array_key_exists($id, $this->instances))
        return $this->instances[$id];
      if(isset($this->makers) && array_key_exists($id, $this->makers))
        return $this->makers[$id];
      if(isset($this->parents))
        foreach($this->parents as $parent)
          if($result = $parent->find($id))
            return $result;
      if(isset(self::$_makers) && array_key_exists($id, self::$_makers))
        return self::$_makers[$id];
      return NULL;
    }
  
    public function maker($id)
    {
      $id = self::key($id);
      if(isset($this->makers) && array_key_exists($id, $this->makers))
        return $this->makers[$id];
      if(isset($this->parents))
        foreach($this->parents as $parent)
          if($result = $parent->maker($id))
            return $result;
      if(isset(self::$_makers) && array_key_exists($id, self::$_makers))
        return self::$_makers[$id];
      return NULL;
    }
  
    public function set($id, $maker, array $arguments = NULL, ?string $source = NULL): void
    {
      if(is_object($maker))
        $this->instance($id, $maker);
      else
        $this->bind($id, $maker, $arguments, $source);
    }

    public function instance($id, $instance): void
    {
      $this->instances ??= [];
      $id = (array)$id;
      $key = self::key($id[0]) ?? uniqid('', true);
      self::$_aliases ??= [];
      foreach($id as $i)
        self::$_aliases[$i] = $key;
      $this->instances[$key] = $instance;
    }

    public function bind($id, $maker, ?array $arguments = NULL, ?string $source = NULL): void
    {
      $this->makers ??= [];
      $maker = ['maker' => $maker];
      if($arguments)
        $maker['arguments'] = $arguments;
      if($source && is_string($maker['maker']))
       {
        $this->sources ??= [];
        $this->sources[$maker['maker']] = $source;
       }
      $id = (array)$id;
      $key = self::key($id[0]) ?? uniqid('', true);
      self::$_aliases ??= [];
      foreach($id as $i)
        self::$_aliases[$i] = $key;
      $this->makers[$key] = $maker;
    }

    public function alias(string $id, $alias): void
    {
      $alias = (array)$alias;
      $key = self::key($id);
      if($key)
        foreach($alias as $i)
          self::$_aliases[$i] = $key;
    }
  
    public function use($other): void
    {
      $this->parents ??= [];
      if(is_array($other))
        foreach($other as $parent)
          $this->parents[] = $parent;
      else
        $this->parents[] = $other;
    }
  
    public static function default($id, $maker, ?array $arguments = NULL, ?string $source = NULL): void
    {
      self::$_makers ??= [];
      $maker = ['maker' => $maker];
      if($arguments)
        $maker['arguments'] = $arguments;
      if($source && is_string($maker['maker']))
       {
        self::$_sources ??= [];
        self::$_sources[$maker['maker']] = $source;
       }
      $id = (array)$id;
      $key = self::key($id[0]) ?? uniqid('', true);
      self::$_aliases ??= [];
      foreach($id as $i)
        self::$_aliases[$i] = $key;
      self::$_makers[$key] = $maker;
    }
 
    public static function defaults(array $bind): void
    {
      foreach($bind as $id => $maker)
       {
        if(is_string($maker))
          $maker = ['class' => $maker];
        static::default(array_filter([$id, $maker['alias']]), $maker['class'] ?? $maker['factory'], $maker['arguments'], $maker['source']);
       }
    }
    protected static function key($id)
    {
      if(isset(self::$_aliases))
        return self::$_aliases[$id];
      return NULL;
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
    protected function make($maker, ?array $arguments = NULL)
    {
      if($maker instanceof \Closure)
       {
        $object = $maker->call($this, $arguments);
        $class = get_class($object);
       }
      elseif(is_string($maker))
       {
        if(!class_exists($maker))
          $this->include($maker);
        if(class_exists($maker))
          $class = $maker;
        else
          throw new FactoryResolutionException("Can`t resolve target class {$maker}.");
       }
      if(!isset($class))
        throw new FactoryResolutionException('Can`t resolve target class.');
      try { $reflector = new \ReflectionClass($class); }
      catch (\ReflectionException $e)
       { throw new FactoryResolutionException("Target class [$class] does not exist.", 0, $e); }
      if(!isset($object))
       {
        if($arguments)
         {
          foreach($arguments as $i => $argument)
            if(is_string($argument) && strpos($argument, '=>') === 0)
              if($instance = $this->get(trim(substr($argument, 2))))
                $arguments[$i] = $instance;
         }
        elseif($constructor = $reflector->getConstructor())
         {
          foreach($constructor->getParameters() as $parameter)
            if($parameter->getClass() !== NULL && ($instance = $this->get($parameter->getClass()->name)))
              $arguments[] = $instance;
            elseif($parameter->isOptional())
              try { $arguments[] = $parameter->getDefaultValue(); } catch(\ReflectionException $e) {}
            else
              throw new FactoryResolutionException("Can`t resolve arguments for class {$class}.");
         }
        $object = $reflector->newInstanceArgs($arguments ?? []);
       }
      if($object instanceof I\FactoryConsumer || in_array('FactoryConsumer', $reflector->getTraitNames(), true))
        $object->factory($this);
      return $object;
    }
  
 }

