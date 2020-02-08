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
      return new Log\Channel($channel, $options);
    }
  
    public function default(string $channel = NULL): Log\I\Channel
    {
      if($channel)
        $this->default = $this->channels[$channel];
      return $this->default;
    }
  
    public function debug(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function info(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function notice(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function warning(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function error(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function critical(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function alert(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function emergency(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }
   
    public function log(...$arguments): Log\I\Channel
    {
      call_user_func_array([$this->default(), __FUNCTION__], $arguments ?? []);
      return $this;
    }

    public function name(): string
    {
      return call_user_func([$this->default(), __FUNCTION__]);
    }
 }
