<?php
    
    namespace glx\core;
    
    use glx\Common\_string;
    use glx\Context;
    use glx\Locale;

    class MultiLingual extends Str implements I\MultiLingual
    {
        protected static string $_type = 'ML';
        
        
        public function get(string $lang = null): _string
        {
            $lang ??= Context::locale()->language();
            if (!($string = $this->value[$lang]) &&
             ($config = Context::config()->core) &&
             ($config = $config->ml) &&
             ($config->defaultIfEmpty === true) &&
             (($lang = $config->default) ||
              ((is_string($config = Context::config()->locale) && ($lang = Locale::get($config)->language())) ||
               (($config = $config->default) && ($lang = Locale::get($config)->language()))))) {
                $string = $this->value[$lang];
            }
            
            return new _string($string ?? '');
        }
    }
 

