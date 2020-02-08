<?php
 
 namespace glx\DB;
 
 
 abstract class QueryCompiler implements I\QueryCompiler
 {
    protected string $unitsCompilerClass;
    
    public function select(iterable $units): array
    {
      $uc = $this->unitsCompiler();
      return ['SELECT ' . implode(" \n", array_filter([
        $uc->columns($units['columns']),
        $uc->from($units['table']),
        $uc->join($units['join']),
        $uc->where($units['where']),
        $uc->order($units['order']),
        $uc->group($units['group']),
        $uc->having($units['having']),
        $uc->limit($units['limit']),
        $uc->offset($units['offset'])])), $uc->bindings()];
    }
  
    public function delete(iterable $units): array
    {
      $uc = $this->unitsCompiler();
      return ['DELETE ' . implode(" \n", array_filter([
        $uc->from($units['table']),
        $uc->where($units['where']),
        $uc->order($units['order']),
        $uc->limit($units['limit'])])), $uc->bindings()];
    }
  
    public function insert(iterable $units): array
    {
      $uc = $this->unitsCompiler();
      $insert = 'INSERT INTO ' . implode(" \n", [
        $uc->table($units['table']),
        $uc->values($units['values'])]);
      if($units['fields'])
        $insert .= ' ON DUPLICATE KEY UPDATE '.$uc->fields($units['fields']);
      return [$insert, $uc->bindings()];
    }
  
    public function update(iterable $units): array
    {
      $uc = $this->unitsCompiler();
      return ['UPDATE ' . implode(" \n", array_filter([
        $uc->table($units['table']),
        $uc->join($units['join']),
        $uc->where($units['where']),
        $uc->order($units['order']),
        $uc->limit($units['limit'])])), $uc->bindings()];
    }
    
    protected function unitsCompiler(): I\UnitsCompiler
    {
      return new $this->unitsCompilerClass;
    }
 }