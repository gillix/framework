<?php
 
 namespace glx\DB\Query;
 
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
  * @method Searchable from($table, string $alias = NULL);
  * @method Searchable limit(int $count, int $offset = NULL)
  * @method Searchable offset(int $offset)
  * @method iterable get($callback = NULL)
  * @method value($column = NULL)
  * @method I\Result one()
  * @method I\Paginated page($page, $pp = NULL)
  * @method iterable column($index = NULL)
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