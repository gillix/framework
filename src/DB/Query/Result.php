<?php
    
    namespace glx\DB\Query;
    
    use glx\Common;

    class Result extends Common\Collection implements I\Result
    {
        protected Common\I\Stopwatch $timer;
        
        public function __construct(array $array, Common\I\Stopwatch $timer = null)
        {
            if ($timer) {
                $timer->finish();
                $this->timer = $timer;
            }
            parent::__construct($array);
        }
        
        public function stat(): array
        {
            return isset($this->timer) ? $this->timer->stat() : [];
        }
        
        // TODO: возможно пребразование значений в нужный тип "на лету" (как узнать нужный тип?)
    }
 