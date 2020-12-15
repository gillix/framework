<?php
    
    namespace glx\Locale;
    
    use Punic\Exception;

    class DateTime extends Localized implements I\DateTime
    {
        protected \DateTimeInterface $time;
        
        public function __construct($locale, \DateTimeInterface $time)
        {
            parent::__construct($locale);
            $this->time = $time;
        }
        
        public function format($format = self::FORMAT_LONG): string
        {
            try {
                return \Punic\Calendar::formatDatetime($this->time, $format, $this->locale->name());
            } catch (Exception $e) {
                return '';
            }
        }
    
        public function formatDate($format = self::FORMAT_LONG): string
        {
            try {
                return \Punic\Calendar::formatDate($this->time, $format, $this->locale->name());
            } catch (Exception $e) {
                return '';
            }
        }
    
        public function formatTime($format = self::FORMAT_LONG): string
        {
            try {
                return \Punic\Calendar::formatDate($this->time, $format, $this->locale->name());
            } catch (Exception $e) {
                return '';
            }
        }
    }