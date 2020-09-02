<?php
    
    namespace glx\DB\Query\I;
    
    
    /**
     * @method Select select(...$columns)
     * @method JoinClause join($table, $on = null, string $type = 'inner')
     * @method JoinClause left($table, $on = null)
     * @method JoinClause right($table, $on = null)
     * @method JoinClause cross($table, $on = null)
     * @method WhereClause where($name, $operator = null, $value = null)
     * @method Select order($by, $direction = null)
     * @method WhereClause having($name, $operator, $value);
     * @method Select group(...$columns);
     * @method Searchable from($table, string $alias = null);
     * @method Searchable limit(int $count, int $offset = null)
     * @method Searchable offset(int $offset)
     * @method iterable get($callback = null)
     * @method value($column = null)
     * @method array one()
     * @method Paginated page($page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    interface WhereClause
    {
        public function or($name, $operator = null, $value = null): self;
        
        public function and($name, $operator = null, $value = null): self;
    }
