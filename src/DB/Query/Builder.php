<?php
 
 namespace glx\DB\Query;
 
 use glx\DB;
 
 abstract class Builder
 {
    protected \ArrayObject $units;
    protected DB\I\Driver $connection;
    protected DB\I\QueryCompiler $compiler;
    
    public function __construct(DB\I\Driver $connection)
    {
      $this->units = new \ArrayObject();
      $this->connection = $connection;
      $this->compiler = $connection->compiler();
    }
    
    protected static function createFrom(Builder $source)
    {
      $new = new static($source->connection);
      $new->units = $source->units;
      return $new;
    }
  
    public function new()
    {
      return new static($this->connection);
    }
  
    public function with()
    {
      // TODO: implement
    }
  
    public function without($sections)
    {
      $new = static::createFrom($this);
      $new->units = new \ArrayObject($this->units->getArrayCopy());
      if(is_string($sections))
        unset($new->units[$sections]);
      elseif(is_array($sections))
        foreach($sections as $section)
          unset($new->units[$section]);
      return $new;
    }
  
    protected function table($table, ?string $alias = NULL)
    {
      if(is_array($table))
        if(is_array($table[0]))
         {
          foreach($table as $item)
            $this->from(...$item);
          return $this;
         }
        else
          return $this->from(...$table);

      $table = ['table' => $table];
      if($alias)
        $table['alias'] = $alias;
      $this->units['table'][] = $table;
      return $this;
    }
 }