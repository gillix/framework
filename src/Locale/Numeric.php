<?php
    
    namespace glx\Locale;
    
    use glx\I\Locale;
    use Punic\Number;

    class Numeric extends Localized implements I\Numeric
    {
        protected $number;
        
        public function __construct(Locale $locale, $number)
        {
            parent::__construct($locale);
            $this->number = $number;
        }
        
        public function format($precision = null): string
        {
            return Number::format($this->number, $precision, $this->locale->name());
        }

//    public function asText(): array
//    {
//
//    }
    }
 