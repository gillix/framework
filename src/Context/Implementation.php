<?php
    
    namespace glx\Context;
    
    use Closure;
    use glx\Cache;
    use glx\Common;
    use glx\Common\I\Collection;
    use glx\Error;
    use glx\Events;
    use glx\HTTP;
    use glx\I\Context;
    use glx\Locale;
    use glx\Log;
    use glx\Logger;

    require_once 'CallStack.php';
    require_once __DIR__ . '/../Cache/Persistent.php';
    require_once __DIR__ . '/../Common/Collection.php';

// require_once __DIR__.'/../Config/ReadOnly.php';
    
    class Implementation implements Context
    {
        private I\CallStack         $callstack;
        private Cache\I\Persistent  $persistent;
        private Common\I\Collection $temporary;
        private \glx\I\Locale       $locale;
        private \glx\I\Logger       $logger;
        private Common\I\Collection $input;
        private Common\I\Collection $config;
        private HTTP\I\Server       $http;
        private array               $options;
        private I\Profile           $profile;
        private Events\Manager      $events;
        
        
        public function __construct(array $options = [])
        {
//      $this->options = $options;
            $this->callstack = new CallStack();
            $this->persistent = new Cache\Persistent($options['cache'] ?? []);
            $this->temporary = new Cache\Temporary();
            $this->events = new Events\Manager();
            $content = [];
            $this->input = new Common\Collection($content);
            if (isset($options['input']) && $options['input'] instanceof Common\I\Collection) {
                $this->input->link($options['input']);
            }
            if (isset($options['config']) && $options['config'] instanceof Common\I\Collection) {
                $this->config = $options['config'];
            }
            $this->profile = new Profile($options['profile'] ?? '');
            if (isset($options['http']) && $options['http'] instanceof HTTP\I\Server) {
                $this->http = $options['http'];
            }
            if (isset($options['locale'])) {
                $this->locale = $options['locale'] instanceof \glx\I\Locale ? $options['locale'] : Locale::get($options['locale']);
            } else {
                $this->locale = Locale::get('en_US');
            }
            if (isset($options['logger'])) {
                $this->logger = $options['logger'] instanceof \glx\I\Logger ? $options['logger'] : new Logger($options['logger']);
            } else {
                $this->logger = new Logger();
            }
        }
        
        public function callstack(): I\CallStack
        {
            return $this->callstack;
        }
        
        public function persistent(): Cache\I\Persistent
        {
            return $this->persistent;
        }

        /**
         * @param string|null $name
         * @return Collection|mixed
         */
        public function temporary(string $name = null): mixed
        {
            if ($name) {
                return $this->temporary[$name];
            }
            
            return $this->temporary;
        }

        /**
         * @param string|null $name
         * @return Collection|mixed
         */
        public function input(string $name = null): mixed
        {
            if ($name) {
                return $this->input[$name];
            }
            
            return $this->input;
        }
        
        public function profile($profile = null): I\Profile
        {
            if ($profile) {
                $this->profile->set($profile);
            }
            
            return $this->profile;
        }
        
        public function locale(\glx\I\Locale $locale = null): \glx\I\Locale
        {
            if ($locale) {
                $this->locale = $locale;
            }
            
            return $this->locale;
        }
        
        public function http(): HTTP\I\Server
        {
            if (!isset($this->http)) {
                throw new Error('HTTP server parameters is not available in non-HTTP context');
            }
            
            return $this->http;
        }
        
        public function log(string $channel = null): Log\I\Channel
        {
            if ($channel) {
                return $this->logger->to($channel);
            }
            
            return $this->logger;
        }
        
        public function config(): Common\I\Collection
        {
            return $this->config ?? new Common\ReadOnlyCollection($empty = []);
        }
        
        public function event(string $name = null)
        {
            if ($name) {
                return new Events\Event($this->events, $name);
            }
            
            return $this->events;
        }
        
        public function with($options, Closure $function): self
        {
            // TODO: создать копию себя с измененными свойствами согласно опций
            // TODO: установить в стек, вызвать колбек, убрать из стека, вернуть результат колбека
        }
    }
 
