<?php
 
 namespace glx\DB\Query;

 
 use glx\DB;

 /**
  * @method int delete(array $where = NULL)
  * @method int update($name, $value = NULL)
  * @method Select select(...$columns)
  * @method SearchableTable where($name, $operator = NULL, $value = NULL)
  * @method SearchableTable order($by, $direction = NULL)
  * @method SearchableTable limit(int $count, int $offset = NULL)
  * @method SearchableTable offset(int $offset)
  * @method iterable get($callback = NULL)
  * @method array one()
  * @method Paginated page($page, $pp = NULL)
  * @method Aggregated aggregated($columns, $page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */
 class Table extends Builder implements I\Table
 {
    public function __construct(DB\I\Driver $connection, $table = null, string $alias = NULL)
    {
      parent::__construct($connection);
      if($table !== null)
        $this->table($table, $alias);
    }
 
    public function insert($fields): int
    {
      $insert = Insert::createFrom($this);
      $insert->values($fields);
      return $insert->perform();
    }
  
    public function __call($name, $arguments)
    {
      return call_user_func_array([SearchableTable::createFrom($this), $name], $arguments);
    }

    public function join($table, $on = NULL, string $type = 'inner'): JoinableTable
    {
      return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on, $type);
    }

    public function left($table, $on = NULL): JoinableTable
    {
      return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
    }

    public function right($table, $on = NULL): JoinableTable
    {
      return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
    }

    public function cross($table, $on = NULL): JoinableTable
    {
      return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
    }
 }
