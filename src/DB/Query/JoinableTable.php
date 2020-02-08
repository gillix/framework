<?php
 
 namespace glx\DB\Query;
 

 class JoinableTable extends Joinable implements I\JoinableTable
 {
 
    public function get($callback = NULL): I\Result
    {
      return Select::createFrom($this)->get($callback);
    }
   
    public function one(): I\Result
    {
      return Select::createFrom($this)->one();
    }
   
    public function page($page, $pp = NULL): I\Paginated
    {
      return Select::createFrom($this)->page($page, $pp);
    }
   
    public function column($index = NULL): I\Result
    {
      return Select::createFrom($this)->column($index);
    }
   
    public function object($class = NULL, $args = NULL)
    {
      return Select::createFrom($this)->object($class, $args);
    }
   
    public function update($name, $value = NULL): int
    {
      return Update::createFrom($this)->set($name, $value)->perform();
    }
   
    public function select(...$columns): I\Select
    {
      return Select::createFrom($this)->select(...$columns);
    }
 
    public function group(...$columns): I\Select
    {
      return Select::createFrom($this)->group(...$columns);
    }
 
    public function value($column = NULL)
    {
      return Select::createFrom($this)->value($column);
    }
   
    public function aggregated(array $columns, $page, $pp = NULL): I\Aggregated
    {
      return Select::createFrom($this)->aggregated($columns, $page, $pp);
    }
 }