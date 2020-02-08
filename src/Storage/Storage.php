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
      $this->id = $id ?? uniqid('', true);
      Manager::register($this->id, $this);
      $this->registry = new Registry();
    }
 
    public function id(): string
    {
      return $this->id;
    }
  
    public function register($object): core\I\ID
    {
      $id = uniqid('', true);
      $this->registry->add($id, $object);
      return new core\ID($this->id, $id);
    }
 
    public static function new($options, \glx\I\Context $context = NULL): I\Storage
    {
      return new static($options, $context);
    }
 }