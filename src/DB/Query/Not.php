<?php
 
 namespace glx\DB\Query;
 
 
 class Not implements I\Not
 {
    protected I\ConditionExpression $expr;
    
    public function __construct(I\ConditionExpression $expr)
    {
      $this->expr = $expr;
    }
 
    public function or($name, $operator = NULL, $value = NULL): I\Sequence
    {
      return _or_($this, cond($name, $operator, $value));
    }

    public function and($name, $operator = NULL, $value = NULL): I\Sequence
    {
      return _and_($this, cond($name, $operator, $value));
    }

    public function expr(): I\ConditionExpression
    {
      return $this->expr;
    }
 }