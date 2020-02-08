<?php
 namespace glx\core;

 class VisibilityLevels 
 {
    protected static $levels = [
       I\Visibility::PUBLIC    => 'public',
       I\Visibility::PROTECTED => 'protected',
       I\Visibility::PRIVATE   => 'private'
    ];
    
    public static function convert($visibility)
    {
      if(is_int($visibility))
        return self::$levels[$visibility];
      if(is_string($visibility))
        return array_flip(self::$levels)[$visibility];
      return NULL;
    }
 }