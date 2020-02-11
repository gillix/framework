<?php
 
 namespace glx\DB\Query\I;
 
 
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
  * @method array one()
  * @method Paginated page($page, $pp = NULL)
  * @method iterable column($index)
  * @method object($class = NULL, $args = NULL)
  */
 interface WhereClause
 {
    public function or($name, $operator = NULL, $value = NULL): self;
    public function and($name, $operator = NULL, $value = NULL): self;
 }
