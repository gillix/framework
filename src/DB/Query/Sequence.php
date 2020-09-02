<?php
    
    namespace glx\DB\Query;
    
    require_once 'Condition.php';
    require_once 'Raw.php';
    require_once 'Not.php';
    
    class Sequence implements I\Sequence
    {
        protected array $entries = [];
        
        public function __construct(...$entries)
        {
            foreach ($entries as $i => $entry) {
                if (is_array($entry)) {
                    $entry = cond($entry);
                } elseif (!$entry instanceof I\ConditionExpression) {
                    continue;
                }
                $this->add($entry);
            }
        }
        
        public function entries(): array
        {
            return $this->entries;
        }
        
        public function or($name, $operator = null, $value = null): self
        {
            return $this->add(cond($name, $operator, $value), 'or');
        }
        
        public function and($name, $operator = null, $value = null): self
        {
            return $this->add(cond($name, $operator, $value));
        }
        
        public function add(I\ConditionExpression $condition, $relation = 'and'): self
        {
            $entry = ['condition' => $condition];
            if (count($this->entries)) {
                $entry['relation'] = $relation;
            }
            $this->entries[] = $entry;
            
            return $this;
        }
    }
    
    function _or_(...$entries): I\Sequence
    {
        $seq = seq();
        foreach ($entries as $i => $entry) {
            if (is_array($entry)) {
                $entry = cond($entry);
            } elseif (!$entry instanceof I\ConditionExpression) {
                continue;
            }
            $seq->add($entry, 'or');
        }
        
        return $seq;
    }
    
    function _and_(...$entries): I\Sequence
    {
        return seq(...$entries);
    }
    
    function seq(...$entries): I\Sequence
    {
        return new Sequence(...$entries);
    }
 