<?php
 
 namespace glx\DB\Query;
 
 
 abstract class Joinable extends Searchable implements I\Joinable
 {
 
    public function join($table, $on = NULL, string $type = 'inner'): I\JoinClause
    {
      /** таблица может быть другим запросом
       */
      $cond = new JoinCondition();
      if($on)
        $cond->init(seq($on));
      if(is_array($table))
        [$table, $alias] = $table;
      $this->units['join'][] = ['table' => $table, 'alias' => $alias ?? '', 'type' => $type, 'condition' => $cond];
      return new JoinClause($this, $cond);
    }
   
    public function left($table, $on = NULL): I\JoinClause
    {
      return $this->join($table, $on, 'left');
    }
   
    public function right($table, $on = NULL): I\JoinClause
    {
      return $this->join($table, $on, 'right');
    }
   
    public function cross($table, $on = NULL): I\JoinClause
    {
      return $this->join($table, $on, 'cross');
    }
 }
 
 