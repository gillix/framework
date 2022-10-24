<?php
    
    namespace glx\Log;
    
    use Monolog\Handler\BrowserConsoleHandler;
    use Monolog\Handler\ChromePHPHandler;
    use Monolog\Handler\ElasticsearchHandler;
    use Monolog\Handler\ErrorLogHandler;
    use Monolog\Handler\FirePHPHandler;
    use Monolog\Handler\NativeMailerHandler;
    use Monolog\Handler\PHPConsoleHandler;
    use Monolog\Handler\RotatingFileHandler;
    use Monolog\Handler\StreamHandler;
    use Monolog\Handler\SyslogHandler;
    use Monolog\Handler\TelegramBotHandler;
    use Monolog\Logger;

    class Channel implements I\Channel
    {
        protected string          $name;
        protected Logger          $logger;
        protected const DEFAULT_HANDLER = 'errorlog';
        protected const DEFAULT_LEVEL   = 'error';
        protected static array $handlers = [
            // TODO: add all other handlers
            // TODO: add default arguments
         'file'       => [StreamHandler::class],
         'rotate'     => [RotatingFileHandler::class],
         'fire'       => [FirePHPHandler::class],
         'console'    => [BrowserConsoleHandler::class],
         'chrome'     => [ChromePHPHandler::class],
         'phpconsole' => [PHPConsoleHandler::class],
         'errorlog'   => [ErrorLogHandler::class, [ErrorLogHandler::OPERATING_SYSTEM, self::DEFAULT_LEVEL]],
         'syslog'     => [SyslogHandler::class],
         'telegram'   => [TelegramBotHandler::class],
         'mail'       => [NativeMailerHandler::class],
         'elastic'    => [ElasticsearchHandler::class],
        ];
        protected static array $levels   = [
         'debug'     => Logger::DEBUG,
         'info'      => Logger::INFO,
         'notice'    => Logger::NOTICE,
         'warning'   => Logger::WARNING,
         'error'     => Logger::ERROR,
         'critical'  => Logger::CRITICAL,
         'alert'     => Logger::ALERT,
         'emergency' => Logger::EMERGENCY,
        ];
        
        public function __construct(string $channel = '', array $options = [])
        {
            $this->name = $channel;
            $handlers = [];
            if ($options) {
                foreach ($options as $handler => $arguments) {
                    $arguments = (array)$arguments;
                    if ($arguments && is_array($arguments) && count($arguments) && ($level = self::$levels[$arguments[count($arguments) - 1]])) {
                        $arguments[count($arguments) - 1] = $level;
                    } else {
                        $arguments[] = self::$levels[self::DEFAULT_LEVEL];
                    }
                    $class = self::$handlers[$handler][0] ?? self::$handlers[self::DEFAULT_HANDLER][0];
                    $handlers[] = new $class(...$arguments);
                }
            }
            if (!$handlers) {
                if (($arguments = self::$handlers[self::DEFAULT_HANDLER][1]) && ($level = self::$levels[$arguments[count($arguments) - 1]])) {
                    $arguments[count($arguments) - 1] = $level;
                } else {
                    $arguments[] = self::$levels[self::DEFAULT_LEVEL];
                }
                $handlers[] = new self::$handlers[self::DEFAULT_HANDLER][0](...$arguments);
            }
            $this->logger = new Logger($channel, $handlers);
        }
        
        public function debug($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function info($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function notice($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function warning($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function error($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function critical($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function alert($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function emergency($message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $message, $context);
        }
        
        public function log($level, $message, array $context = []): void
        {
            call_user_func([$this->logger, __FUNCTION__], $level, $message, $context);
        }
        
        public function name(): string
        {
            return $this->name;
        }
    }
