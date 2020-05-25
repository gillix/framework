<?php

 namespace glx;
 
 use glx\Cache;
 use glx\Common;
 use glx\HTTP;
 use glx\Log;
 use glx\Events;

 require_once 'I/Context.php';
 require_once 'Context/CallStack.php';
 require_once 'Context/Implementation.php';
 require_once 'Cache/Persistent.php';
 require_once 'Common/Collection.php';
 
 /**
  * @method static Context\Profile profile($profile = NULL)
  * @method static Cache\I\Persistent persistent()
  * @method static temporary(string $name = NULL)
  * @method static Context\I\Callstack callstack()
  * @method static \glx\I\Locale locale(\glx\I\Locale $locale = NULL)
  * @method static Common\I\Collection input(string $name = NULL)
  * @method static Events\I\Event|Events\Manager event(string $name = NULL)
  * @method static ?Common\I\Collection config()
  * @method static HTTP\I\Server http()
  * @method static Log\I\Channel log(string $channel = NULL)
  */
 class Context
 {
    private static \SplStack $context;
    
    public const DEFAULT_PROFILE = 'default';
  
    public static function get(): I\Context
    {
      self::init();
      if(self::$context->isEmpty())
        self::new();
      return self::$context->top();
    }
  
    public static function new(array $options = []): I\Context
    {
      self::init();
      self::$context->push(new Context\Implementation($options));
      return self::$context->top();
    }
  
    public static function release(): void
    {
      if(!isset(self::$context) || self::$context->isEmpty()) return;
      self::$context->pop();
    }
  
    private static function init(): void
    {
      if(!isset(self::$context))
        self::$context = new \SplStack();
    }
  
    public static function __callStatic($name, $arguments)
    {
      $context = self::get();
      if(method_exists($context, $name))
        return call_user_func_array([$context, $name], $arguments);
      return NULL;
    }
 }
 
