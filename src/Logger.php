<?php
    
    namespace glx;
    
    use glx\Log;

    class Logger implements I\Logger
    {
        protected array         $channels = [];
        protected Log\I\Channel $default;
        
        public function __construct(array $options = [])
        {
            if (isset($options['default'])) {
                $default = $options['default'];
                unset($options['default']);
            }
            foreach ($options as $channel => $handlers) {
                $this->add($channel, $handlers);
            }
            if (!$this->channels) {
                $this->add('default');
            }
            if (!isset($default)) {
                $default = 'default';
            }
            $this->default($default);
        }
        
        public function to(string $channel): Log\I\Channel
        {
            return $this->channels[$channel] ?? new Log\Dummy();
        }
        
        public function add($channel, array|null $options = null): void
        {
            if (is_string($channel)) {
                $channel = self::new($channel, $options);
            }
            $this->channels[$channel->name()] = $channel;
        }
        
        public static function new($channel, array|null $options = null): Log\I\Channel
        {
            return new Log\Channel($channel, $options ?? []);
        }
        
        public function default(string|null $channel = null): Log\I\Channel
        {
            if ($channel) {
                $this->default = $this->channels[$channel];
            }
            
            return $this->default;
        }
        
        public function debug(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function info(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function notice(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function warning(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function error(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function critical(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function alert(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function emergency(\Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $message, $context);
        }
        
        public function log($level, \Stringable|string $message, array $context = []): void
        {
            call_user_func([$this->default(), __FUNCTION__], $level, $message, $context);
        }
        
        public function name(): string
        {
            return call_user_func([$this->default(), __FUNCTION__]);
        }
    }
