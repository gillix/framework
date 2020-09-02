<?php
    
    namespace glx\DB\Query;
    
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
     * @method I\Result one()
     * @method I\Paginated page($page, $pp = null)
     * @method iterable column($index = null)
     * @method object($class = null, $args = null)
     */
    class WhereClause extends QueryClause implements I\WhereClause
    {
        protected I\Sequence $where;
        
        public function __construct(I\Searchable $target, I\Sequence $where)
        {
            parent::__construct($target);
            $this->where = $where;
        }
        
        public function or($name, $operator = null, $value = null): I\WhereClause
        {
            $this->where->add(Condition::fetch($name, $operator, $value), 'or');
            
            return $this;
        }
        
        public function and($name, $operator = null, $value = null): I\WhereClause
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