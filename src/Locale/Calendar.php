<?php
    
    namespace glx\Locale;
    
    use glx\Exception;
    use glx\Locale\I;

    class Calendar extends Localized implements I\Calendar
    {
        
        
        public function format(\DateTimeInterface $time, $format = I\DateTime::FORMAT_LONG): string
        {
            try {
                return (new DateTime($this->locale, $time))->format($format);
            } catch (Exception $e) {
                return '';
            }
        }
    
        public function formatDate(\DateTimeInterface $time, $format = I\DateTime::FORMAT_LONG): string
        {
            try {
                return (new DateTime($this->locale, $time))->formatDate($format);
            } catch (Exception $e) {
                return '';
            }
        }
    
        public function formatTime(\DateTimeInterface $time, $format = I\DateTime::FORMAT_LONG): string
        {
            try {
                return (new DateTime($this->locale, $time))->formatTime($format);
            } catch (Exception $e) {
                return '';
            }
        }
    
        public function formatInterval(\DateTimeInterface $from, \DateTimeInterface $to, $format = DateTime::FORMAT_LONG): string
        {
        
        }
    }
