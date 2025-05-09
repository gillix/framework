<?php
    
    namespace glx;
    
    use Closure;
    use glx\Locale\Calendar;
    use glx\Locale\Currency;
    use glx\Locale\DateTime;
    use glx\Locale\Monetary;
    use glx\Locale\Numeric;
    use Punic\Data;
    use Punic\Territory;

    // This implementation requires punic package
    
    include_once 'I/Locale.php';
    include_once 'Locale/I/Calendar.php';
    include_once 'Locale/I/Currency.php';
    
    class Locale implements I\Locale
    {
        protected string                 $name;
        protected string                 $language;
        protected Locale\I\Monetary      $monetary;
        protected Locale\I\Calendar      $calendar;
        protected static array           $locales;
        
        public function __construct(string $locale)
        {
            $this->name = $locale;
            $this->language = Data::explodeLocale($locale)['language'];
        }
        
        public function name(): string
        {
            return $this->name;
        }
        
        public function language(): string
        {
            return $this->language;
        }
        
        public function monetary(): Locale\I\Monetary
        {
            if (!isset($this->monetary)) {
                $this->monetary = new Monetary($this);
            }
            
            return $this->monetary;
        }
        
        public function numeric($number): Locale\I\Numeric
        {
            return new Numeric($this, $number);
        }
        
        public function time($time = null): Locale\I\DateTime
        {
            return new DateTime($this, $time);
        }
        
        public function calendar($type = 'gr'): Locale\I\Calendar
        {
            if (!isset($this->calendar)) {
                $this->calendar = new Calendar($this);
            }
            
            return $this->calendar;
        }
        
        public function currency($currency = null): Locale\I\Currency
        {
            return new Currency($this, $currency ?? \Punic\Currency::getCurrencyForTerritory(Data::getTerritory($this->name)));
        }
        
        public static function get(string $locale, string|null $region = null, string|null $script = null): I\Locale
        {
            if (!self::valid($valid = self::normalize($locale, $region, $script)) && !self::valid($valid = self::normalize($locale, $region)) && !self::valid($valid = $locale)) {
                throw new Exception("Unknown locale: {$locale}");
            }
            
            return new static($valid);
        }
        
        public static function valid(string $locale, string|null $region = null, string|null $script = null): bool
        {
            return in_array(self::normalize($locale, $region, $script), self::locales(), true);
        }
        
        protected static function locales(): array
        {
            // TODO: сделать общий хаб фабрики для внутреннего использования
            // TODO: проверять изменения директории и сбрасывать кеш
            if (!isset(self::$locales)) {
                $cache = new Cache\Persistent();
                if (!($locales = $cache->get($key = Data::getDataDirectory()))) {
                    $locales = Data::getAvailableLocales();
                    $cache->store($key, $locales);
                }
                self::$locales = $locales;
            }
            
            return self::$locales;
        }
        
        protected static function normalize(string $locale, string|null $region = null, string|null $script = null): string
        {
            if ($region) {
                $locale = implode('-', array_filter([$locale, $script, $region]));
            } elseif (strpos($locale, '_')) {
                $locale = str_replace('_', '-', $locale);
            }
            
            return $locale;
        }
        
        public static function for(string $country, string|null $script = null): ?I\Locale
        {
            if ($langs = Territory::getLanguages($country, 'o', true)) {
                return self::get($langs[0], $country, $script);
            }
            
            return null;
        }
        
        public static function list($condition): array
        {
            $list = [];
            if (is_array($condition)) {
                foreach ($condition as $locale) {
                    $list[$locale] = new static($locale);
                }
            } elseif ($condition instanceof Closure) {
                foreach (self::locales() as $locale) {
                    $list[$locale] = $condition(new static($locale));
                }
            }
            
            return $list;
        }
    }
