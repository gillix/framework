<?php
 namespace glx\Library;

 use glx\Exception;

 /**
  * Class Components
  * Exterior of default factory
  * @package glx\Library
  *
  * @method static bool has(string $id)
  * @method static get(string $id, $default = NULL)
  * @method static new(string $id, $default = NULL, array $arguments = NULL)
  * @method static set(string $id, $maker)
  * @method static default($id, $maker, ?array $arguments = NULL, ?string $source = NULL)
  */
 class Components
 {
    protected static I\Factory $factory;
    
    public static function factory(): I\Factory
    {
      if(!isset(self::$factory))
        self::$factory = new Factory();
      return self::$factory;
    }
    
    public static function __callStatic(string $name, array $arguments = [])
    {
      $factory = static::factory();
      if(method_exists($factory, $name))
        return call_user_func_array([$factory, $name], $arguments);
      throw new Exception("Components: trying to call undefined method {$name}");
    }
 }