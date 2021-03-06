<?php
    
    namespace glx\Locale;
    
    use Closure;

    class Monetary extends Localized implements I\Monetary
    {
        public function currencies($condition): array
        {
            $list = [];
            if (is_array($condition)) {
                foreach ($condition as $code) {
                    $list[$code] = new Currency($this->locale, $code);
                }
            } elseif ($condition instanceof Closure) {
                foreach (\Punic\Currency::getAllCurrencies(false, false, $this->locale->name()) as $code => $name) {
                    $list[$code] = $condition(new Currency($this->locale, $code));
                }
            }
            
            return $list;
        }
        
        public function format($number, $currency = null, string $width = '', string $kind = 'standard'): string
        {
            return (new Currency($this->locale, $currency))->format($number, $width, $kind);
        }
    }