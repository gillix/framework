<?php
 namespace glx\core;

 class Joint extends Cheater implements I\Joint, I\Visibility
 {
    protected I\Binder $_binder;
    protected I\Joint $_parent;
    protected I\Joint $_owner;
    
    public function __construct(I\Binder $binder, I\Joint $parent)
    {
      $this->_parent = $parent;
      if($binder instanceof I\Joint)
       {
        $this->_binder = $binder->_binder;
        $this->_owner = $binder->parent();
       }
      else
       {
        $this->_binder = $binder;
        $this->_owner = $this->_parent;
       }
    }
 
    public function name(): string { return $this->_binder->name(); }
    public function parent(): I\Joint { return $this->_parent; }
    public function owner(): I\Joint { return $this->_owner ?? $this->_parent; }
    public function closest($type): I\Joint
    {
      if($this->origin() instanceof I\Node && $this->origin()->is($type)) return $this;
      return $this->parent()->closest($type);
    }
  
    public function location(): string { return $this->owner()->location().$this->name().($this->origin() instanceof I\Node ? '/' : NULL); }
    public function path(?array $options = NULL): string
    {
      return $this->parent()->path($options).
             $this->name().
            ($this->origin() instanceof I\Node ? '/' : NULL).
          ((($options !== NULL && $options['clean'] !== true) && $this->origin()->is('NODE')) ? $this->origin()->extend($options) : NULL);
    }
    public function childOf(I\Joint $parent): bool { return $this->parent()->sameAs($parent) || $this->parent()->childOf($parent); }
    public function origin(): I\Entity { return $this->_binder->origin(); }
    public function visibility(): int { return $this->_binder->visibility(); }
    public function profile(): string { return $this->_binder->profile(); }
    
    protected function _cheat(I\Joint $joint = NULL)
    {
      $this->origin()->_cheat($joint);
    }
 
    public function __call($name, $arguments)
    {
      $origin = $this->origin();
      $this->_cheat($this);
      $result = call_user_func_array([$origin, $name], $arguments);
      $this->_cheat();
      return $result;
    }
  
    public function __get($name)
    {
      $this->_cheat($this);
      $result = $this->origin()->$name;
      $this->_cheat();
      return $result;
    }
  
    public function __set($name, $value)
    {
      $this->_cheat($this);
      $this->origin()->$name = $value;
      $this->_cheat();
    }
  
    public function __isset($name)
    {
      $this->_cheat($this);
      $result = isset($this->origin()->$name);
      $this->_cheat();
      return $result;
    }
   
    public function __unset($name)
    {
      $this->_cheat($this);
      unset($this->origin()->$name);
      $this->_cheat();
    }

    public function visibleFor(I\Joint $node): bool
    {
      if($this->visibility() === I\Visibility::PRIVATE && !$this->parent()->sameAs($node))
        return false;
      if($this->visibility() === I\Visibility::PROTECTED && !$this->parent()->sameAs($node) && !$node->childOf($this->parent()) && !$node->inherits($this->parent()))
        return false;
      return true;
    }
  
    public function root(): I\Joint
    {
      return $this->parent()->root();
    }
  
    public function __toString()
    {
      $origin = $this->origin();
      if(method_exists($origin, '__toString'))
        return (string)$origin;
      // бросать исключение?
      return $this->path();
    }
 }