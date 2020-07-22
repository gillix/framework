<?php
 namespace glx\Log;
 
 class Channel implements I\Channel
 {
    protected string $name;
    protected \Monolog\Logger $logger;
    protected const DEFAULT_HANDLER = 'errorlog';
    protected const DEFAULT_LEVEL = 'error';
    protected static array $handlers = [
       // TODO: add all other handlers
       'file'     => \Monolog\Handler\StreamHandler::class,
       'rotate'   => \Monolog\Handler\RotatingFileHandler::class,
       'fire'     => \Monolog\Handler\FirePHPHandler::class,
       'console'  => \Monolog\Handler\BrowserConsoleHandler::class,
       'chrome'   => \Monolog\Handler\ChromePHPHandler::class,
       'phpconsole' => \Monolog\Handler\PHPConsoleHandler::class,
       'errorlog' => \Monolog\Handler\ErrorLogHandler::class,
       'syslog'   => \Monolog\Handler\SyslogHandler::class,
       'telegram' => \Monolog\Handler\TelegramBotHandler::class,
       'mail'     => \Monolog\Handler\NativeMailerHandler::class,
       'elastic'  => \Monolog\Handler\ElasticsearchHandler::class,
    ];
    protected static array $levels = [
       'debug'     => \Monolog\Logger::DEBUG,
       'info'      => \Monolog\Logger::INFO,
       'notice'    => \Monolog\Logger::NOTICE,
       'warning'   => \Monolog\Logger::WARNING,
       'error'     => \Monolog\Logger::ERROR,
       'critical'  => \Monolog\Logger::CRITICAL,
       'alert'     => \Monolog\Logger::ALERT,
       'emergency' => \Monolog\Logger::EMERGENCY,
    ];
    
    public function __construct(string $channel = '', array $options = [])
    {
      $this->name = $channel;
      $handlers = [];
      if($options)
        foreach($options as $handler => $arguments)
         {
          $arguments = (array)$arguments;
          if($arguments && is_array($arguments) && count($arguments) && ($level = self::$levels[$arguments[count($arguments) - 1]]))
            $arguments[count($arguments) - 1] = $level;
          else
            $arguments[] = self::$levels[self::DEFAULT_LEVEL];
          $class = self::$handlers[$handler] ?? self::$handlers[self::DEFAULT_HANDLER];
          $handlers[] = new $class(...$arguments);
         }
      if(!$handlers)
        $handlers[] = new self::$handlers[self::DEFAULT_HANDLER](self::$levels[self::DEFAULT_LEVEL]);
      $this->logger = new \Monolog\Logger($channel, $handlers);
    }
 
    public function debug($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function info($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function notice($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function warning($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function error($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function critical($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function alert($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function emergency($message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $message, $context);
      return $this;
    }
   
    public function log($level, $message, array $context = []): I\Channel
    {
      call_user_func([$this->logger, __FUNCTION__], $level, $message, $context);
      return $this;
    }
 
    public function name(): string
    {
      return $this->name;
    }
 }