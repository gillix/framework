<?php
    
    namespace glx\Locale;
    
    use glx\Locale;

    class Localized
    {
        protected \glx\I\Locale $locale;
    
        /**
         * Localized constructor.
         * @param \glx\I\Locale|string $locale
         * @throws \glx\Exception
         */
        public function __construct($locale)
        {
            if (is_string($locale)) {
                $locale = Locale::get($locale);
            }
            $this->locale = $locale;
        }
    }