<?php
    
    namespace glx\Locale;
    
    
    class DateTime extends Localized implements I\DateTime
    {
        protected \DateTime $time;
        
        public function __construct($locale, $time)
        {
            parent::__construct($locale);
            $this->time = $time;
        }
        
        public function format($format = null): string
        {
        
        }
    }