<?php

 namespace glx\core;
 

 class Image extends File implements I\Image
 {
    protected static string $_type = 'IMAGE';
    protected array $info;
  
    public function __construct($options = NULL)
    {
      if(is_array($options))
       {
        if($options['info'])
          $this->info = $options['info'];
       }
      parent::__construct($options);
    }
  
    public function info(string $param = NULL)
    {
      if($param) return $this->info[$param];
      return $this->info ?? [];
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
      return "<img alt='' src=\"{$this->url()}\" {$this->info('attributes')}/>";
    }
 }
 
 // register class as value resolver
 Unit::resolver(Image::class);


 /**
  * global function for simplify usage
  * creates new object of Image
  * @param mixed ...$arguments
  * @return I\Image
  */
 function image(...$arguments): I\Image
 {
   return Image::new(...$arguments);
 }
