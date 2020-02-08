<?php

 namespace glx\core;
 
 use glx\Context;

 class Method extends Unit implements I\Invokable
 {
    protected static string $_type = 'METHOD';
    protected \Closure $function;
    protected $source;
  
    public function __construct($options = NULL)
    {
      if($options instanceof \Closure)
        $this->function = $options;
      elseif(is_string($options))
        $this->source = $options;
      elseif(is_array($options))
       {
        if($function = $options['function'])
          $this->function = $function;
        elseif($options['source'])
          $this->source = $options['source'];
       }
      parent::__construct(is_array($options) ? $options : []);
    }
  
    public function call(array $arguments = NULL)
    {
      if($caller = $this->parent())
        return $this->apply($caller, $arguments);
      // может быть выдавать исключение?
      return NULL;
    }
  
    public function apply(I\Joint $caller, array $arguments = NULL)
    {
      if(!isset($this->function) && isset($this->source))
        $this->function = static::embody((string)$this->source);
      if(isset($this->function))
       {
        $callstack = Context::get()->callstack();
        try
         {
          ob_start();
          $callstack->enter($this->this());
          return $this->function->call($caller, ...($arguments ?? [])) ?? ob_get_contents();
         }
        catch(\glx\Exception $e)
         {
          $e->out(ob_get_contents());
          throw $e;
         }
        finally
         {
          $callstack->release();
          ob_end_clean();
         }
       }
      // может быть выдавать исключение?
      return NULL;
    }
 
    protected static function embody(string $source)
    {
      $context = Context::get();
      if(is_file($source) && ($function = include($source)) instanceof \Closure)
        return $function;
      return NULL;
    }
  
    public static function super(array $arguments = NULL): I\Invokable
    {
      $callstack = Context::get()->callstack();
      if($callstack->empty()) return NULL;
     
      $method = $callstack->current();
      if($super = $method->parent()->super()->get($method->name(), 'method'))
        $super->apply($method->parent(), $arguments);
      return NULL;
    }
  
    public static function new(...$arguments): I\Invokable
    {
      return new static(...$arguments);
    }
  
    public static function resolve($value): ? I\Invokable
    {
      if($value instanceof \Closure)
        return self::new($value);
      return NULL;
    }
 }
 
 // register class as value resolver
 Unit::resolver(Method::class);


 /**
  * global function for simplify usage
  * creates new object of Method
  * @param mixed ...$arguments
  * @return I\Invokable
  */
 function method(...$arguments): I\Invokable
 {
   return Method::new(...$arguments);
 }
 
 /**
  * global function for simplify usage
  * calls parent of overloaded method
  * @param array $arguments
  * @return mixed
  */
 function super(array $arguments = NULL)
 {
   return Method::super($arguments);
 }
 