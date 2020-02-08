<?php
 
 namespace glx\core;
 
 class Super implements I\Super
 {
    private I\Joint $inheritor;
    private \ArrayObject $ancestors;
    
    use AccessProxy;
   
    public function __construct(\ArrayObject $ancestors, I\Joint $inheritor = NULL)
    {
      $this->ancestors = $ancestors ?? new \ArrayObject();
      $this->inheritor = $inheritor;
    }
 
    public function get(string $name, $type = NULL): ? I\Joint
    {
      foreach($this->ancestors as &$ancestor)
       {
        $ancestor = $this->resolve($ancestor);
        if($property = $ancestor->obtain($name, $type))
          return new Joint($property, $this->inheritor);
       }
      return NULL;
    }
  
    protected function resolve($ancestor): I\Joint
    {
      if(!is_string($ancestor)) return $ancestor;

      $name = $ancestor;
      $type = 'NODE';

      if((strrpos($name, ':')) !== false)
        return $this->inheritor->get($name, $type);

      if(($locator = $this->inheritor->owner()) && ($ancestor = $locator->get($name, $type)))
        return $ancestor;
      throw new Exception('Can`t find ancestor object: '.$name);

       
      /*
      if($locator = $this->inheritor->owner())
       {
        if(($pos = strpos($name, '/')) === 0)
          return $locator->explore($name, $type);
        elseif($pos !== false)
         {
          [$my, $rest] = explode('/', $name, 2);
          $rest = trim($rest, '/');
          if($found = $locator->property($my, $type) ?? $locator->findUp($my, $type))
            if(!$rest)
              return $found;
            else
              return $found->explore($rest, $type);
          return NULL;
         }
        if($ancestor = $locator->property($name, $type) ?? $locator->findUp($name, $type))
          return $ancestor;
       }
      throw new Exception('Can`t find ancestor object: '.$name);
      */
    }
   
    public function add($ancestor, string $name = NULL): void
    {
      if($ancestor instanceof I\Binder)
        $this->ancestors[$name ?? $ancestor->name()] = $ancestor->origin();
      elseif($ancestor instanceof I\Entity)
        if($name)
          $this->ancestors[$name] = $ancestor;
        else
          $this->ancestors[] = $ancestor;
      elseif(is_array($ancestor))
        foreach($ancestor as $key => $item)
          $this->add($item, is_string($key) ? $key : NULL);
      elseif(is_string($ancestor))
        $this->ancestors[$name ?? $ancestor] = $ancestor;
    }
   
    public function fix($inheritor): I\Super
    {
      if($this->inheritor !== $inheritor && count($this->ancestors))
        return new Super($this->ancestors, $inheritor);
      return $this;
    }
  
    public function in(string $ancestor): I\Super
    {
      if($item = $this->ancestors[$ancestor])
        return new Super(new \ArrayObject([$item]), $this->inheritor);
      return $this;
    }

    public function is(string $metatype, bool $not = false): bool
    {
      foreach($this->ancestors as $ancestor)
       {
        $ancestor = $this->resolve($ancestor);
        if($ancestor->isMeta($metatype, $not) || $ancestor->super()->is($metatype, $not))
          return !$not;
       }
      return $not;
    }
  
    public function isAncestor(I\Joint $ancestor): bool
    {
      foreach($this->ancestors as $item)
        if($item->sameAs($ancestor) || $item->super()->isAncestor($ancestor))
          return true;
      return false;
    }

    public function select($condition, I\Selection $list = NULL): I\Selection
    {
      $list ??= new Selection();
      foreach($this->ancestors as $item)
        $list = $list->extend($item->select($condition));
      return $list;
    }
 }
