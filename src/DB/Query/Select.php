<?php
 
 namespace glx\DB\Query;
 
 use glx\Common;
 
 class Select extends Joinable implements I\Select
 {
    use Query;
 
    public function get($callback = NULL): I\Result
    {
      // We need to select a specified column
      if(is_string($callback))
       {
        $new = self::createFrom($this);
        $new->units['columns'] = [$callback];
        return $new->column();
       }
      // Select only specified columns
      if(is_array($callback))
       {
        $new = self::createFrom($this);
        $new->units['columns'] = $callback;
        return $new->get();
       }
      $stopwatch = Common\Stopwatch::start();
      return new Result($this->fetch($callback, $stopwatch), $stopwatch);
    }
   
    protected function fetch($callback = NULL, Common\I\Stopwatch $stopwatch = NULL)
    {
      [$sql, $values] = $this->compile();
      
//      if($callback instanceof \Closure)
        $result = $this->connection->perform(function($query, $values) use($callback, $stopwatch) {
                 $stmt = $this->connection->prepare($query);
                 if($stopwatch) $stopwatch->tick('preparation');
                 if($values) $this->connection::bind($stmt, $values);
                 $stmt->execute();
                 if($stopwatch) $stopwatch->tick('execution');
                 if($callback instanceof \Closure)
                   return $callback($stmt);
                 $fetch = (array)$callback;
                 $fetch[0] ??= \PDO::FETCH_ASSOC;
                 return $stmt->fetchAll(...$fetch);
              }, $sql, $values);
//      else
//        $result = $this->connection->query($sql, $values, is_int($callback) ? $callback : NULL);
      return $result;
    }
  
    public function one(): I\Result
    {
      $stopwatch = Common\Stopwatch::start();
      return new Result($this->limit(1)->fetch(static function(\PDOStatement $stmt){ return $stmt->fetch(\PDO::FETCH_ASSOC) ?: []; }, $stopwatch), $stopwatch);
    }
   
    public function page($page, $pp = Paginated::DEFAULT_PER_PAGE, $callback = NULL): I\Paginated
    {
      // TODO: move aggregate functions to the separate unit for wide db compatibility
      $stopwatch = Common\Stopwatch::start();
      $countable = $this->without(['select', 'order', 'limit', 'offset']);
      if($countable->units['group'])
        $countable = $countable->new()->from($countable->select('COUNT(*)'), 'c');
      $total = $countable->value('COUNT(*)');
      $stopwatch->tick('count');
      $result = $this->offset(($page - 1) * $pp)->limit($pp)->fetch($callback, $stopwatch);
      $stopwatch->tick('execution');
      return new Paginated($result, $total, $page, $pp, $stopwatch);
    }
   
    public function aggregated(array $columns, $page, $pp = NULL, $callback = NULL): I\Aggregated
    {
      $stopwatch = Common\Stopwatch::start();
      foreach($columns as $name => $function)
        $columns[$name] = "$function($name) AS $name";
      $columns['total'] = 'COUNT(*) AS total';
      $aggregates = $this->new()->from($this->without(['order', 'limit', 'offset']), 'a')->select($columns)->one();
      $stopwatch->tick('aggregates');
      $result = $this->offset(($page - 1) * $pp)->limit($pp)->fetch($callback, $stopwatch);
      return new Aggregated($result, $aggregates->array(), $page, $pp, $stopwatch);
    }
  
    public function column($column = NULL): I\Result
    {
      if($column && is_string($column))
        return $this->get($column);
     
      return $this->get(static function(\PDOStatement $stmt) use($column) {
         return $stmt->fetchAll(\PDO::FETCH_COLUMN, is_int($column) ? $column : 1);
      });
    }
   
    public function object($class = NULL, $args = NULL)
    {
      return $this->limit(1)->fetch(static function(\PDOStatement $stmt) use($class, $args) {
         return $stmt->fetchObject(...array_filter([$class ?? 'stdClass', $args]));
      });
    }
 
    public function value($column = NULL)
    {
      if($column)
       {
        $new = self::createFrom($this);
        $new->units['columns'][] = $column;
       }
      $new ??= $this;
      return $new->limit(1)->fetch(static function(\PDOStatement $stmt) { return $stmt->fetchColumn(); });
    }
   
    public function compile(): array
    {
      return $this->compiler->select($this->units);
    }
   
    public function select(...$columns): I\Select
    {
      $this->units['columns'] = $this->units['columns'] ? array_merge($this->units['columns'], $columns) : $columns;
      return $this;
    }
   
    public function having($name, $operator, $value): I\WhereClause
    {
      $expr = Condition::fetch($name, $operator, $value);
      if(!isset($this->units['having']))
        $this->units['having'] = $expr instanceof I\Sequence ? $expr : seq();
      else
        $this->units['having']->add($expr);
      return new WhereClause($this, $this->units['having']);
    }
   
    public function from($table, string $alias = NULL): I\Select
    {
      return $this->table($table, $alias);
    }
 
    public function group(...$columns): I\Select
    {
      $this->units['group'] = $this->units['group'] ? array_merge($this->units['group'], $columns) : $columns;
      return $this;
    }
 }
