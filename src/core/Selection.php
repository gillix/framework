<?php
 
 namespace glx\core;
 
 use glx\core\I\Visibility;
 use glx\Context;

 class Selection extends \ArrayObject implements I\Selection
 {
    public function add($item): I\Selection
    {
      $this->append($item);
      return $this;
    }

    public function remove($item): I\Selection
    {
      if(($i = array_search($item, $this->array(), true)) !== false)
        unset($this[$i]);
      return $this;
    }
  
 // возможно нужен итератор или индекс как аргумент
    public function each($callback, $arguments = []): I\Selection
    {
      $walker = NULL;
      if($callback instanceof I\Joint && $callback->origin() instanceof I\Invokable)
        $walker = function($item) use($callback, $arguments) {
          if($item instanceof I\Joint && $item->origin() instanceof I\Caller)
            echo $item->call($callback, $arguments);
        };
      elseif(is_string($callback))
        $walker = function($item) use($callback, $arguments) {
          if($item instanceof I\Joint && $item->origin() instanceof I\Caller && ($method = $item->get($callback, 'method')))
            echo $item->call($method, $arguments);
        };
      elseif($callback instanceof \Closure)
        $walker = function($item) use($callback, $arguments) {
           $callback->call($item, $arguments);
        };
      if($walker) array_walk($this->array(), $walker);
      return $this;
    }
  
    public function call($callback, $arguments = []): string
    {
      try
       {
        ob_start();
        $this->each($callback, $arguments);
        return ob_get_contents();
       }
      finally
       {
        ob_end_clean();
       }
    }
  
    public function filter($condition = NULL): I\Selection
    {
      $walker = NULL;
      if(!$condition)
       {
        $this->replace(array_filter($this->array()));
        return $this;
       }
      if($condition instanceof I\Joint && $condition->origin() instanceof I\Invokable)
        $walker = function($item) use ($condition) {
          $condition->call($item);
        };
      else
       {
        $types = NULL;
        if(is_string($condition))
          $types = [$condition];
        elseif(is_array($condition))
          $types = $condition;
        if($types)
          $walker = function($item) use ($types) {
             foreach($types as $type)
               if($item->is($type)) return true;
             return false;
          };
        elseif($condition instanceof \Closure)
          $walker = $condition;
       }
      if($walker) $this->replace(array_filter($this->array(), $walker));
      return $this;
    }
  
    public function sort($by, int $way = I\Sort::ASC): I\Selection
    {
      $walker = NULL;
      if($by instanceof I\Joint && $by->origin() instanceof I\Invokable)
        $walker = function($a, $b) use ($by, $way) {
          $by->call($a, $b, $way);
        };
      elseif(is_string($by))
        $walker = function(I\Joint $a, I\Joint $b) use ($by, $way) {
          $a = $a->get($by);
          $x = $a && $a->is('PROPERTY') ? $a->get() : ($a instanceof I\Printable ? (string)$a : $a);
          $b = $b->get($by);
          $y = $b && $b->is('PROPERTY') ? $b->get() : ($b instanceof I\Printable ? (string)$b : $b);
          return $x == $y ? 0 : ($x < $y ? -1 : 1) * $way;
        };
      elseif($by instanceof \Closure)
        $walker = $by;
      if($walker) $this->uasort($walker);
      return $this;
    }

    public function limit(int $limit, int $offset = 0): I\Selection
    {
      $this->replace(array_slice($this->array(), $offset, $limit));
      return $this;
    }

    public function offset($offset): I\Selection
    {
      $this->replace(array_slice($this->array(), $offset));
      return $this;
    }
  
    public function map(\Closure $callback)
    {
      return new static(array_map($callback, $this->array()));
    }
  
    protected function array(): array
    {
      $array = $this->exchangeArray([]);
      $this->exchangeArray($array);
      return $array;
    }

    protected function replace(array $new)
    {
      return $this->exchangeArray($new);
    }

    public function extend(I\Selection $other): I\Selection
    {
      if(!$other->count()) return $this;
      $my = $their = [];
      foreach($this as $item)
        $my["{$item->name()}:{$item->profile()}:{$item->type()}"] = $item;
      foreach($other as $item)
        $their["{$item->name()}:{$item->profile()}:{$item->type()}"] = $item;
      return new static(array_values(array_merge($my, $their)));
    }
 }

