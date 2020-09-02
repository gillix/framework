<?php
    
    namespace glx\Common;
    
    
    class Stopwatch implements I\Stopwatch
    {
        protected array $ticks;
        
        public function __construct()
        {
            $this->tick('begin');
        }
        
        public static function start(): self
        {
            return new static();
        }
        
        public function tick(string $label): _float
        {
            $tick = $this->ticks[$label] = microtime(true) * 1000;
            
            return new _float($tick);
        }
        
        public function finish(): _float
        {
            return $this->tick('end');
        }
        
        public function elapsed(string $label = null, string $from = null): _float
        {
            if ($label === null) {
                $this->tick($label = 'end');
            }
            if (!($tick = $this->ticks[$label])) {
                $tick = $this->tick($label)->get();
            }
            if ($from === null) {
                $labels = array_keys($this->ticks);
                $from = $this->ticks[$labels[array_search($label, $labels, true) - 1]];
            } else {
                if (array_key_exists($from, $this->ticks)) {
                    $from = 'begin';
                }
                $from = $this->ticks[$from];
            }
            
            return new _float($tick - $from);
        }
        
        public function stat(): array
        {
            if (!isset($this->ticks['ens'])) {
                $this->tick($label = 'end');
            }
            $prev = null;
            $stat = [];
            foreach ($this->ticks as $label => $tick) {
                if ($prev !== null) {
                    if ($label === 'end') {
                        $stat['total'] = $tick - $this->ticks['begin'];
                    } else {
                        $stat[$label] = $tick - $prev;
                    }
                }
                $prev = $tick;
            }
            
            return $stat;
        }
        
        public function __toString()
        {
            // TODO: возможно переделать
            return (string)$this->elapsed()->round(_float::ROUND_HALF, 2)/*->format('%.9f')*/ ;
        }
        
    }