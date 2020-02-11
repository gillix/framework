<?php
 
 namespace glx\DB\Query\I;
 
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
  * @method array one()
  * @method Paginated page($page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */
 interface SearchableTable extends Searchable
 {
    public function delete(array $where = NULL): int;
 }
