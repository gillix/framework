<?php
 namespace glx\Locale;
 
 class Numeric extends Localized implements I\Numeric
 {
    protected $number;
    
    public function __construct(\glx\I\Locale $locale, $number)
    {
      parent::__construct($locale);
      $this->number = $number;
    }
 
    public function format($precision = NULL): string
    {
      return \Punic\Number::format($this->number, $precision, $this->locale->name());
    }
  
//    public function asText(): array
//    {
//
//    }
 }
 