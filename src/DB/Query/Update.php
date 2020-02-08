<?php
 
 namespace glx\DB\Query;
 
 class Update extends Joinable implements I\Update
 {
    use Query;
 
    public function compile(): array
    {
      return $this->compiler->update($this->units);
    }
   
    public function set($name, $value = NULL): I\Update
    {
      $this->units['fields'][$name] = $value;
      return $this;
    }
   
    public function table($table, string $alias = NULL): I\Update
    {
      return parent::table($table, $alias);
    }
   
    public function perform(): int
    {
      [$sql, $values] = $this->compile();
      return $this->connection->execute($sql, $values);
    }
 }