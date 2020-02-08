<?php
 // возможно не нужен
 namespace glx\DB\Query;
 
 
 trait Query
 {
    public function __toString(): string
    {
      [$sql, $values] = $this->compile();
      $sql = (string)str_replace(array_keys($values), array_map(fn($item) => is_string($item) ? "'$item'" : $item, $values), $sql);
      return $sql;
    }
 }