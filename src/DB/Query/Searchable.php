<?php
    
    namespace glx\DB\Query;
    
    require_once 'Sequence.php';
    
    abstract class Searchable extends Builder implements I\Searchable
    {
        
        public function where($name, $operator = null, $value = null): I\WhereClause
        {
            $expr = Condition::fetch($name, $operator, $value);
            if (!isset($this->units['where'])) {
                $this->units['where'] = $expr instanceof I\Sequence ? $expr : seq($expr);
            } else {
                $this->units['where']->add($expr);
            }
            
            return new WhereClause($this, $this->units['where']);
        }
        
        public function order($by, $direction = null): I\Searchable
        {
            $this->units['order'][$by] = $direction ?? 'asc';
            
            return $this;
        }
        
        public function limit(int $count, int $offset = null): I\Searchable
        {
            $this->units['limit'] = $count;
            if ($offset !== null) {
                $this->offset($offset);
            }
            
            return $this;
        }
        
        public function offset(int $offset): I\Searchable
        {
            $this->units['offset'] = $offset;
            
            return $this;
        }
    }