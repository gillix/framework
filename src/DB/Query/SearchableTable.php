<?php
 
 namespace glx\DB\Query;
 
 use glx\DB;
 
 /**
  * @method int update($name, $value = NULL)
  * @method Select select(...$columns)
  * @method JoinableTable join($table, $on = NULL, string $type = 'inner')
  * @method JoinableTable left($table, $on = NULL)
  * @method JoinableTable right($table, $on = NULL)
  * @method JoinableTable cross($table, $on = NULL)
  * @method SearchableTable where($name, $operator = NULL, $value = NULL)
  * @method SearchableTable order($by, $direction = NULL)
  * @method SearchableTable limit(int $count, int $offset = NULL)
  * @method SearchableTable offset(int $offset)
  * @method iterable get($callback = NULL)
  * @method I\Result one()
  * @method Paginated page($page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */
 class SearchableTable extends Searchable implements I\SearchableTable
 {
    public function __construct(DB\I\Driver $connection, $table = null, string $alias = NULL)
    {
      parent::__construct($connection);
      if($table !== null)
        $this->table($table, $alias);
    }
 
    public function delete(array $where = NULL): int
    {
      $query = Delete::createFrom($this);
      if($where !== null)
        $query->where($where);
      return $query->perform();
    }

    public function __call($name, $arguments)
    {
      return call_user_func_array([JoinableTable::createFrom($this), $name], $arguments);
    }
 }
 
