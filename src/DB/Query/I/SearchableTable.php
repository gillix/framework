<?php
    
    namespace glx\DB\Query\I;
    
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
     * @method array one()
     * @method Paginated page($page, $pp = null)
     * @method iterable column($index)
     * @method object($class = null, $args = null)
     */
    interface SearchableTable extends Searchable
    {
        public function delete(array $where = null): int;
    }
