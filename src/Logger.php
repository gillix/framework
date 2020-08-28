<?php
 namespace glx;
 
 use glx\Log;
 
 class Logger implements I\Logger
 {
    protected array $channels = [];
    protected Log\I\Channel $default;
    
    public function __construct(array $options = [])
    {
      if($options['default'])
       {
        $default = $options['default'];
        unset($options['default']);
       }
      foreach($options as $channel => $handlers)
        $this->add($channel, $handlers);
      if(!$this->channels)
        $this->add('default');
      if(!isset($default)) $default = 'default';
      $this->default($default);
    }
 
    public function to(string $channel): Log\I\Channel
    {
      return $this->channels[$channel] ?? new Log\Dummy();
    }
   
    public function add($channel, array $options = NULL): void
    {
      if(is_string($channel))
        $channel = self::new($channel, $options);
      $this->channels[$channel->name()] = $channel;
    }
   
    public static function new($channel, array $options = NULL): Log\I\Channel
    {
      return new Log\Channel($channel, $options ?? []);
    }
  
    public function default(string $channel = NULL): Log\I\Channel
    {
      if($channel)
        $this->default = $this->channels[$channel];
      return $this->default;
    }
  
    public function debug($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function info($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function notice($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function warning($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function error($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function critical($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function alert($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function emergency($message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $message, $context);
      return $this;
    }

    public function log($level, $message, array $context = []): Log\I\Channel
    {
      call_user_func([$this->default(), __FUNCTION__], $level, $message, $context);
      return $this;
    }

    public function name(): string
    {
      return call_user_func([$this->default(), __FUNCTION__]);
    }
 }
