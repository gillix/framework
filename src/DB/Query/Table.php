<?php
    
    namespace glx\DB\Query;
    
    
    use glx\DB;

    /**
     * @method int delete(array $where = null)
     * @method int update($name, $value = null)
     * @method Select select(...$columns)
     * @method SearchableTable where($name, $operator = null, $value = null)
     * @method SearchableTable order($by, $direction = null)
     * @method SearchableTable limit(int $count, int $offset = null)
     * @method SearchableTable offset(int $offset)
     * @method iterable get($callback = null)
     * @method I\Result one()
     * @method Paginated page($page, $pp = null)
     * @method Aggregated aggregated($columns, $page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    class Table extends Builder implements I\Table
    {
        public function __construct(DB\I\Driver $connection, $table = null, string $alias = null)
        {
            parent::__construct($connection);
            if ($table !== null) {
                $this->table($table, $alias);
            }
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
        
        public function join($table, $on = null, string $type = 'inner'): JoinableTable
        {
            return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on, $type);
        }
        
        public function left($table, $on = null): JoinableTable
        {
            return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
        }
        
        public function right($table, $on = null): JoinableTable
        {
            return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
        }
        
        public function cross($table, $on = null): JoinableTable
        {
            return call_user_func([JoinableTable::createFrom($this), __METHOD__], $table, $on);
        }
    }
