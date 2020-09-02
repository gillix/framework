<?php
    
    namespace glx\DB\Query\I;
    
    
    /**
     * @method int delete(array $where = null)
     * @method int update($name, $value = null)
     * @method Select select(...$columns)
     * @method SearchableTable where($name, $operator = null, $value = null)
     * @method SearchableTable order($by, $direction = null)
     * @method SearchableTable limit(int $count, int $offset = null)
     * @method SearchableTable offset(int $offset)
     * @method iterable get($callback = null)
     * @method array one()
     * @method Paginated page($page, $pp = null)
     * @method Aggregated aggregated($columns, $page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    interface Table
    {
        public function insert($fields): int;
    }