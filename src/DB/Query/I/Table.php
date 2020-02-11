<?php
 
 namespace glx\DB\Query\I;
 
 
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
 interface Table
 {
    public function insert($fields): int;
 }