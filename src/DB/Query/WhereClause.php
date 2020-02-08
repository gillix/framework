<?php
 
 namespace glx\DB\Query;
 
 // TODO: добавить вирт. интерфейс
 /**
  * @method I\Select select(...$columns)
  * @method I\JoinClause join($table, $on = NULL, string $type = 'inner')
  * @method I\JoinClause left($table, $on = NULL)
  * @method I\JoinClause right($table, $on = NULL)
  * @method I\JoinClause cross($table, $on = NULL)
  * @method I\WhereClause where($name, $operator = NULL, $value = NULL)
  * @method I\Select order($by, $direction = NULL)
  * @method I\WhereClause having($name, $operator, $value);
  * @method I\Select group(...$columns);
  * @method I\Searchable from($table, string $alias = NULL);
  * @method I\Searchable limit(int $count, int $offset = NULL)
  * @method I\Searchable offset(int $offset)
  * @method iterable get($callback = NULL)
  * @method array one()
  * @method I\Paginated page($page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */

 class WhereClause extends QueryClause implements I\WhereClause
 {
    protected I\Sequence $where;
    
    public function __construct(I\Searchable $target, I\Sequence $where)
    {
      parent::__construct($target);
      $this->where = $where;
    }
  
    public function or($name, $operator = NULL, $value = NULL): I\WhereClause
    {
      $this->where->add(Condition::fetch($name, $operator, $value), 'or');
      return $this;
    }
  
    public function and($name, $operator = NULL, $value = NULL): I\WhereClause
    {
      $this->where->add(Condition::fetch($name, $operator, $value), 'and');
      return $this;
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