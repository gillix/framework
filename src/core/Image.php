<?php

 namespace glx\core;
 

 class Image extends File implements I\Image
 {
    protected static string $_type = 'IMAGE';
    protected array $dimensions;
  
    public function __construct($options = NULL)
    {
      if(is_array($options))
       {
        if($options['dimensions'])
          $this->dimensions = $options['dimensions'];
       }
      parent::__construct($options);
    }
  
    public function dimensions(): array
    {
      return $this->dimensions ?? [];
    }
 
    public static function new(...$arguments): I\Image
    {
      return new static(...$arguments);
    }
  
    public static function resolve($value): ? I\Image
    {
      if(is_string($value) && is_file($value))
        return self::new($value);
      return NULL;
    }
 
    public function __toString()
    {
      $dim = $this->dimensions ?? [];
      return "<img src=\"{$this->url()}\"".(count($dim) ? " width=\"{$dim['width']}\" height=\"{$dim['height']}\"" : NULL).'/>';
    }
 }
 
 // register class as value resolver
 Unit::resolver(Image::class);


 /**
  * global function for simplify usage
  * creates new object of File
  * @param mixed ...$arguments
  * @return I\File
  */
 function file(...$arguments): I\File
 {
   return File::new(...$arguments);
 }
