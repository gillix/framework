<?php
 namespace glx\Locale;
 

 class Currency extends Localized implements I\Currency
 {
    protected string $code;
  
    public function __construct($locale, string $code)
    {
      parent::__construct($locale);
      $this->code = $code;
    }
 
    public function code(): string
    {
      return $this->code;
    }

    public function symbol(string $width = ''): string
    {
      return \Punic\Currency::getSymbol($this->code, $width, $this->locale->name());
    }

    public function name(): string
    {
      return $this->for(NULL);
    }
 
    /**
     * Get name of currency regarding specified quantity
     * @param number|string $quantity identifier ( number for representation or 'zero'|'one'|'two'|'few'|'many'|'other' plural rule)
     * @return string quantitative representation of currency name
     */
    public function for($quantity): string
    {
      return \Punic\Currency::getName($this->code, $quantity, $this->locale->name());
    }

    public function format($number, string $width = '', string $kind = 'standard'): string
    {
      try { return \Punic\Number::formatCurrency($number, $this->code, $kind, null, $width, $this->locale->name()); }
      catch(\Punic\Exception\ValueNotInList $e) { return ''; }
    }
 }