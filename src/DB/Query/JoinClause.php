<?php
 
 namespace glx\DB\Query;
 

 use glx\DB\Exception;

 /**
  * @method Select select(...$columns)
  * @method JoinClause join($table, $on = NULL, string $type = 'inner')
  * @method JoinClause left($table, $on = NULL)
  * @method JoinClause right($table, $on = NULL)
  * @method JoinClause cross($table, $on = NULL)
  * @method WhereClause where($name, $operator = NULL, $value = NULL)
  * @method Select order($by, $direction = NULL)
  * @method WhereClause having($name, $operator, $value);
  * @method Select group(...$columns);
  * @method I\Searchable from($table, string $alias = NULL);
  * @method I\Searchable limit(int $count, int $offset = NULL)
  * @method I\Searchable offset(int $offset)
  * @method iterable get($callback = NULL)
  * @method I\Result one()
  * @method Paginated page($page, $pp = NULL)
  * @method Aggregated aggregated($page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */
 class JoinClause extends QueryClause implements I\JoinClause
 {
    protected I\JoinCondition $cond;
    
    public function __construct(I\Joinable $target, I\JoinCondition $cond)
    {
      parent::__construct($target);
      $this->cond = $cond;
    }
  
    public function on($name, $operator = NULL, $value = NULL): I\WhereClause
    {
      $on = cond($name, $operator, $value);
      if($this->cond->inited() && ($cond = $this->cond->condition()))
       {
        if($cond['type'] === 'on')
         ($seq = $cond['condition'])->add($on);
        else
          throw new Exception('Join condition already set to "USING"');
       }
      else
        $this->cond->init($seq = seq($on));
      return new WhereClause($this->target, $seq);
    }
  
    public function using($field): I\Joinable
    {
      $field = (array)$field;
      if($this->cond->inited() && ($cond = $this->cond->condition()))
       {
        if($cond['type'] === 'using')
          $cond['condition'] = array_merge($cond['condition'], $field);
        else
          throw new Exception('Join condition already set to "ON"');
       }
      else
        $this->cond->init($field, 'using');
      return $this->target;
    }
  
    public function __call($name, $arguments)
    {
      return call_user_func_array([$this->target, $name], $arguments);
    }

    public function __toString()
    {
      return (string)$this->target;
    }
 }