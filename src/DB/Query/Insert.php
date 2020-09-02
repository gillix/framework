<?php
    
    namespace glx\DB\Query;
    
    class Insert extends Builder implements I\Insert
    {
        use Query;
        
        public function set($field, $value = null): I\Insert
        {
            if (is_array($field)) {
                return $this->values($field);
            }
            $this->units['values'][$field] = $value;
            
            return $this;
        }
        
        public function values($values): I\Insert
        {
            if ($values instanceof Select) {
                $this->units['values'] = $values;
            }
            foreach ($values as $field => $value) {
                $this->set($field, $value);
            }
            
            return $this;
        }
        
        public function fields($fields): I\Insert
        {
            return $this->values($fields);
        }
        
        public function orUpdate(...$fields): I\Insert
        {
            if (!count($fields)) {
                $fields = $this->units['values'];
            } else {
                $fields = array_intersect_key($this->units['values'], array_flip($fields));
            }
            $this->units['fields'] = $fields;
            
            return $this;
        }
        
        public function table($table, string $alias = null): I\Insert
        {
            return parent::table($table, $alias);
        }
        
        public function into($table, string $alias = null): I\Insert
        {
            return $this->table($table, $alias);
        }
        
        public function compile(): array
        {
            return $this->compiler->insert($this->units);
        }
        
        public function perform(): int
        {
            [$sql, $values] = $this->compile();
            
            return $this->connection->execute($sql, $values);
        }
    }
 