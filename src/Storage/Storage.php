<?php
 
 namespace glx\Storage;
 
 use glx\core;

 include_once 'Registry.php';
 
 abstract class Storage implements I\Storage
 {
    protected $id;
    protected Registry $registry;
   
    public function __construct($id = NULL, array $options = [], \glx\I\Context $context = NULL)
    {
      $this->id = $id ?? self::generate();
      Manager::register($this->id, $this);
      $this->registry = new Registry();
    }
 
    public function id(): string
    {
      return $this->id;
    }
  
    public function register($object): core\I\ID
    {
      $id = self::generate();
      $this->registry->add($id, $object);
      return new core\ID($this->id, $id);
    }
  
    protected static function generate()
    {
      return md5(uniqid('storage', true));
    }
 
    public static function new($options, \glx\I\Context $context = NULL): I\Storage
    {
      return new static($options, $context);
    }
 }