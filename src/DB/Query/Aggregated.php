<?php
 
 namespace glx\DB\Query;

 use glx\Common;

 class Aggregated extends Paginated implements I\Aggregated
 {
    protected array $aggregated;
    
    public function __construct(array &$array, array $aggregated, $page = 1, $perPage = Paginated::DEFAULT_PER_PAGE, Common\I\Stopwatch $time = NULL)
    {
      $this->aggregated = $aggregated;
      parent::__construct($array, $aggregated['total'], $page, $perPage, $time);
    }
 
    public function aggregated(string $field)
    {
      return $this->aggregated[$field];
    }
 }