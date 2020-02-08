<?php

namespace glx\Config;

require_once 'I/Reader.php';

class Reader implements I\Reader
{
   protected static string $default = 'gcml';
   protected string $parser;
   
   public function __construct(string $format = NULL)
   {
     if($format)
      {
       $format = strtolower($format);
       if(!self::valid($format))
         $format = NULL;
      }
     if($format === NULL) $format = self::$default;
     $this->parser = self::class($format);
   }
 
   public function parse(string $content): array
   {
     return $this->parser::parse($content, [ 'include' => static function($value){
        $include = (array)$value;
        $result = [];
        foreach($include as $path)
          $result = array_merge($result, static::read($path));
        return $result;
     }]);
   }
   
   public static function get(string $format = NULL): I\Reader
   {
     return new static($format);
   }
   
   protected static function valid(string $format): bool
   {
     // depends autoloader to be used
     return class_exists(self::class($format));
   }
 
   protected static function class(string $format): string
   {
     return "\glx\Config\\{$format}\Parser";
   }
 
   public static function default(string $format = NULL): string
   {
     if($format)
      {
       $format = strtolower($format);
       if(self::valid($format)) self::$default = $format;
      }
     return self::$default;
   }
   
   public static function read(string $path): array
   {
     if(!is_file($path))
       throw new \glx\Exception("Can`t read config file: '{$path}' is not exist.");
     $format = (new \SplFileInfo($path))->getExtension();
     return static::get($format)->parse(file_get_contents($path));
   }
}