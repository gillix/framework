<?php
    
    namespace glx\DB\Query;
    
    
    class Delete extends Searchable implements I\Delete
    {
        use Query;
        
        public function from($table, string $alias = null): I\Delete
        {
            return $this->table($table, $alias);
        }
        
        public function compile(): array
        {
            return $this->compiler->delete($this->units);
        }
        
        public function perform(): int
        {
            [$sql, $values] = $this->compile();
            
            return $this->connection->execute($sql, $values);
        }
    }