<?php
 
 namespace glx\DB;
 
 
 abstract class UnitsCompiler implements I\UnitsCompiler
 {
    protected array $values = [];
 
    protected static array $operators = [
      'like'    => 'LIKE',
      'regexp'  => 'REGEXP',
      'in'      => 'IN',
      '!in'     => 'NOT IN',
      'null'    => 'IS NULL',
      '!null'   => 'IS NOT NULL',
      'equal'   => '=',
      'ne'      => '!=',
      'nes'     => '<=>',
      'date'    => 'date',
      'day'     => 'day',
      'month'   => 'month',
      'year'    => 'year',
      'period'  => 'period',
      'greater' => '>',
      'goe'     => '>=',
      'less'    => '<',
      'loe'     => '<=',
      'from'    => '>=',
      'to'      => '<=',
      'between' => 'BETWEEN',
      'default' => '='
    ];
    
    protected static array $aliases = [
      'rlike' => 'regexp',
      '#'     => 'regexp',
      '~#'    => 'rlike',
      '~'     => 'like',
      '='     => 'equal',
      'in'    => 'equal',
      '!='    => 'ne',
      '<>'    => 'ne',
      '<=>'   => 'nes',
      '>'     => 'greater',
      '>='    => 'goe',
      '<'     => 'less',
      '<='    => 'loe',
    ];
    
    protected static int $count = 0;
    protected int $id;
    
    public function __construct()
    {
      static::$count++;
      $this->id = static::$count;
    }
  
    public function bindings(): array
    {
      return $this->values;
    }
 
    public function columns($data): string
    {
      // TODO: distinct
      $data = $data && count($data) ? $data : ['*'];
      return $this->list($data);
    }

    public function where($data): string
    {
      if(!$data) return '';
      return "WHERE {$this->sequence($data)}";
    }
   
    public function order($data): string
    {
      if(!$data) return '';
      foreach($data as $by => $direction)
       {
        $direction ??= 'ASC';
        $direction = strtoupper($direction);
        $data[$by] = "$by $direction";
       }
      return "ORDER BY {$this->list($data)}";
    }
   
    public function limit($data): string
    {
      if(!$data) return '';
      return "LIMIT {$data}";
    }
   
    public function offset($data): string
    {
      if(!$data) return '';
      return "OFFSET {$data}";
    }
   
    public function join($data): string
    {
      if(!$data) return '';
      $join = [];
      foreach($data as $item)
       {
        [$table, $alias, $type, $condition] = [$item['table'], $item['alias'], strtoupper($item['type'] ?? 'inner'), $item['condition']->condition()];
        if($condition['type'] === 'using')
          $cond = "USING ({$this->list($condition['condition'])})";
        else
          $cond = "ON ({$this->sequence($condition['condition'])})";
        $join[] = "{$type} JOIN {$this->table([$item])} $cond";
       }
      return $this->list($join, ' ');
    }
   
    public function table($data): string
    {
      if(!$data) return '';
      $tables = [];
      foreach($data as $item)
       {
        [$table, $alias] = [$item['table'], $item['alias']];
        if($table instanceof Query\I\QueryClause)
          $table = $table->target();
        if($table instanceof Query\I\Select)
          $table = "({$this->sub($table)})";
        $alias = $alias ? " $alias" : NULL;
        $tables[] = $table.$alias;
       }
      return $this->list($tables);
    }
   
    public function from($data): string
    {
      if(!$data) return '';
      return "FROM {$this->table($data)}";
    }
   
    public function group($data): string
    {
      if(!$data) return '';
      return "GROUP BY {$this->list($data)}";
    }

    public function having($data): string
    {
      if(!$data) return '';
      return "HAVING {$this->sequence($data)}";
    }
   
    public function fields($data): string
    {
      if(!$data) return '';
      foreach($data as $name => $value)
        $data[$name] = "{$this->escape($name)} = {$this->mark($value)}";
      return $this->list($data);
    }
   
    public function set($data): string
    {
      if(!$data) return '';
      return "SET {$this->fields($data)}";
    }
   
    public function values($data): string
    {
      if(!$data) return '';
      if(is_array($data) && is_int(key($data)) && is_array($data[0]))
       {
        // insert multiple rows
        $average = array_intersect_key(...$data);
        $rows = [];
        foreach($data as $row)
         {
          $row = array_intersect_key($row, $average);
          $rows[] = "({$this->mark($row)})";
         }
        return "({$this->listNames(array_keys($average))}) VALUES {$this->list($rows)}";
       }
      if($data instanceof Query\I\QueryClause)
        $data = $data->target();
      if($data instanceof Query\I\Select)
        return $this->sub($data);
      return "({$this->listNames(array_keys($data))}) VALUES ({$this->mark($data)})";
    }
  
    protected function condition($field, $operator, $value): string
    {
      $operator = strtolower(static::$aliases[$operator] ?? $operator);
      $cond = '';
      switch($operator)
       {
         case 'like':
           if(is_array($value))
             $cond = "{$field} REGEXP {$this->mark($this->list($value, '|'))}";
           else
             $cond = "{$field} LIKE {$this->mark("%$value%")}";
           break;
         case 'date':
         case 'day':
         case 'month':
         case 'year':
           $operator = strtoupper($operator);
           if(is_array($value) || $value instanceof Query\I\Select || $value instanceof Query\I\QueryClause)
             $cond = "$operator({$field}) IN ({$this->mark($value)})";
           else
             $cond = "$operator({$field}) = {$this->mark($value)}";
           break;
         case 'period':
           $cond = "{$field} >= {$this->mark($value[0])} AND {$field} < DATE_ADD('{$this->mark($value[1])}', INTERVAL 1 DAY)";
           break;
         case 'from':
         case 'to':
           $value = $value === NULL || (is_string($value) && strtolower($value) === 'now') ? Query\raw('NOW()') : $value;
           $operator = static::$operators[$operator];
           $cond = "{$field} {$operator} {$this->mark($value)}";
           break;
         case 'between':
           $cond = "{$field} BETWEEN {$this->mark($value[0])} AND {$this->mark($value[1])}";
           break;
         case 'null':
         case '!null':
           $operator = static::$operators[$operator];
           $cond = "{$field} {$operator}";
           break;
         case 'equal':
         case 'ne':
           $operator = static::$operators[$operator];
           $not = $operator === '!=' ? 'NOT ' : NULL;
           if(is_array($value) || $value instanceof Query\I\Select || $value instanceof Query\I\QueryClause)
             $cond = "{$field} {$not}IN ({$this->mark($value)})";
           else
             $cond = "{$field} {$operator} {$this->mark($value)}";
           break;
         case 'in':
         case '!in':
           $operator = static::$operators[$operator];
           $cond = "{$field} {$operator} ({$this->mark($value)})";
           break;
         default:
           $operator = static::$operators[$operator] ?: static::$operators['default'];
           $cond = "{$field} {$operator} {$this->mark($value)}";
       }
      return $cond;
    }
  
    protected function escape(string $name): string
    {
        // TODO: we can escape just name of field or table, but not all together
      return "$name";
    }
    
    protected function list($items, $glue = ', ')
    {
      return implode($glue, $items);
    }
  
    protected function listNames(array $items, $glue = ', ')
    {
      return $this->list(array_map(fn($name) => $this->escape($name), $items), $glue);
    }
  
    protected function listMarks($values, $glue = ', ')
    {
      return $this->list(array_map( fn($val) => $this->mark($val), $values), $glue);
    }
  
    protected function raw(Query\Raw $data): string
    {
      $sql = $data->raw();
      foreach($data->values() as $value)
        $sql = preg_replace('/\?/', $this->mark($value), $sql, 1);
      return $sql;
    }
  
    protected function not(Query\Not $data): string
    {
      return "!({$this->resolve($data->expr())})";
    }
  
    protected function resolve(Query\I\ConditionExpression $expr): string
    {
      if($expr instanceof Query\I\Sequence)
        return $this->sequence($expr);
      if($expr instanceof Query\I\Condition)
        return $this->condition($expr->name(), $expr->operator(), $expr->value());
      if($expr instanceof Query\Not)
        return $this->not($expr);
      if($expr instanceof Query\Raw)
        return $this->raw($expr);
      return '';
    }
  
    protected function sub(Query\I\Select $query): string
    {
      [$sql, $values] = $query->compile();
      $this->values = array_merge($this->values, $values ?? []);
      return $sql;
    }
  
    protected function sequence(Query\I\Sequence $data): string
    {
      $entries = $data->entries();
      $result = '';
      foreach($entries as $entry)
       {
        $relation = $entry['relation'] ? " {$entry['relation']} " : NULL;
        $result .= $relation . $this->resolve($entry['condition']);
       }
      return $result;
    }
  
    protected function mark($value): string
    {
      if($value instanceof Query\I\QueryClause)
        $value = $value->target();
      if($value instanceof Query\I\Select)
        return $this->sub($value);
      if($value instanceof Query\Raw)
        return $this->raw($value);
      if(is_array($value))
        return $this->listMarks($value);
      $name = ":param_{$this->id}_".(count($this->values) + 1);
      $this->values[$name] = $value;
      return $name;
    }
  }
