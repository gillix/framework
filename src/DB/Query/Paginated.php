<?php
 
 namespace glx\DB\Query;
 
 use glx\Common;

 class Paginated extends Result implements I\Paginated
 {
    protected int $total;
    protected int $page;
    protected int $perPage;
    
    public const DEFAULT_PER_PAGE = 20;
    
    public function __construct(array &$array, $total, $page = 1, $perPage = self::DEFAULT_PER_PAGE, Common\I\Stopwatch $time = NULL)
    {
      $this->total = $total;
      $this->page = $page;
      $this->perPage = $perPage;
      parent::__construct($array, $time);
    }
 
    public function total(): int
    {
      return $this->total;
    }
   
    public function page(): int
    {
      return $this->page;
    }
   
    public function perPage(): int
    {
      return $this->perPage;
    }
   
    public function from(): int
    {
      return ($this->page - 1) * $this->perPage + 1;
    }
   
    public function to(): int
    {
      return $this->page * $this->perPage;
    }
 }
