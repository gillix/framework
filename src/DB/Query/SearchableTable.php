<?php
    
    namespace glx\DB\Query;
    
    use glx\DB;

    /**
     * @method int update($name, $value = null)
     * @method Select select(...$columns)
     * @method JoinableTable join($table, $on = null, string $type = 'inner')
     * @method JoinableTable left($table, $on = null)
     * @method JoinableTable right($table, $on = null)
     * @method JoinableTable cross($table, $on = null)
     * @method SearchableTable where($name, $operator = null, $value = null)
     * @method SearchableTable order($by, $direction = null)
     * @method SearchableTable limit(int $count, int $offset = null)
     * @method SearchableTable offset(int $offset)
     * @method iterable get($callback = null)
     * @method I\Result one()
     * @method value($column = null)
     * @method I\Paginated page($page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    class SearchableTable extends Searchable implements I\SearchableTable
    {
        public function __construct(DB\I\Driver $connection, $table = null, string $alias = null)
        {
            parent::__construct($connection);
            if ($table !== null) {
                $this->table($table, $alias);
            }
        }
        
        public function delete(array $where = null): int
        {
            $query = Delete::createFrom($this);
            if ($where !== null) {
                $query->where($where);
            }
            
            return $query->perform();
        }
        
        public function __call($name, $arguments)
        {
            return call_user_func_array([JoinableTable::createFrom($this), $name], $arguments);
        }
    }
 
