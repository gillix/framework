<?php
    
    namespace glx\DB\Query;
    
    
    use glx\DB\Exception;

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
     * @method I\Searchable from($table, string $alias = null);
     * @method I\Searchable limit(int $count, int $offset = null)
     * @method I\Searchable offset(int $offset)
     * @method iterable get($callback = null)
     * @method I\Result one()
     * @method Paginated page($page, $pp = null)
     * @method Aggregated aggregated($page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    class JoinClause extends QueryClause implements I\JoinClause
    {
        protected I\JoinCondition $cond;
        
        public function __construct(I\Joinable $target, I\JoinCondition $cond)
        {
            parent::__construct($target);
            $this->cond = $cond;
        }
        
        public function on($name, $operator = null, $value = null): I\WhereClause
        {
            $on = cond($name, $operator, $value);
            if ($this->cond->inited() && ($cond = $this->cond->condition())) {
                if ($cond['type'] === 'on') {
                    ($seq = $cond['condition'])->add($on);
                } else {
                    throw new Exception('Join condition already set to "USING"');
                }
            } else {
                $this->cond->init($seq = seq($on));
            }
            
            return new WhereClause($this->target, $seq);
        }
        
        public function using($field): I\Joinable
        {
            $field = (array)$field;
            if ($this->cond->inited() && ($cond = $this->cond->condition())) {
                if ($cond['type'] === 'using') {
                    $cond['condition'] = array_merge($cond['condition'], $field);
                } else {
                    throw new Exception('Join condition already set to "ON"');
                }
            } else {
                $this->cond->init($field, 'using');
            }
            
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