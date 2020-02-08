<?php
 
 namespace glx\DB\Query;
 
 
 class Condition implements I\Condition
 {
    protected string $name;
    protected string $operator;
    protected $value;
   
    public function __construct($name, $operator = NULL, $value = NULL)
    {
      if(is_array($name))
        [$name, $operator, $value] = $name;
      if($value === NULL)
       {
        $value = $operator;
        $operator = is_array($value) ? 'in' : '=';
       }
      $this->name = $name;
      $this->operator = $operator;
      $this->value = $value;
    }
  
    /**
     * @return string
     */
    public function name(): string
    {
      return $this->name;
    }
 
    /**
     * @return string
     */
    public function operator(): string
    {
      return $this->operator;
    }
 
    /**
     * @return mixed
     */
    public function value()
    {
      return $this->value;
    }
  
    public function or($name, $operator = NULL, $value = NULL): I\Sequence
    {
      return _or_($this, cond($name, $operator, $value));
    }

    public function and($name, $operator = NULL, $value = NULL): I\Sequence
    {
      return _and_($this, cond($name, $operator, $value));
    }

    public static function fetch($name, $operator = NULL, $value = NULL): I\ConditionExpression
    {
      if(is_array($name))
       {
        if(is_array($name[0]) || $name[0] instanceof I\ConditionExpression)
          $expr = seq(...$name);
        else
          $expr = cond($name);
       }
      elseif($name instanceof I\ConditionExpression)
        $expr = $name;
      elseif(is_string($name) && $operator === NULL && $value === NULL)
        $expr = raw($name);
      else
        $expr = cond($name, $operator, $value);
      return $expr;
    }
  
 }

 function cond($name, $operator = NULL, $value = NULL): I\ConditionExpression
 {
   if($name instanceof I\ConditionExpression) return $name;
   if(is_string($name) && $operator === NULL && $value === NULL) return raw($name);
   return new Condition($name, $operator, $value);
 }