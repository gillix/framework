<?php
 
 namespace glx\HTTP\Server;
 
 use glx\HTTP;
 
 class Response extends HTTP\Response implements I\Response
 {
    protected string $contentType = '';
    
    public function __construct()
    {
      parent::__construct([]);
    }
 
    public function header($name, string $value = NULL): string
    {
      if(is_array($name))
       {
        foreach($name as $n => $v)
          $this->header($n, $v);
        return '';
       }
      elseif($value)
        $this->headers[$name] = $value;
      return $this->headers[$name];
    }
  
    public function headers(array $headers = NULL): array
    {
      if($headers !== NULL)
        $this->headers = $headers;
      return $this->headers;
    }
 
    public function body(string $content = NULL): string
    {
      if($content !== NULL)
        $this->body = $content;
      return $this->body;
    }
 
    public function contentType(string $type = NULL): string
    {
      if($type !== NULL)
        $this->contentType = $type;
      return $this->contentType;
    }
   
    public function status(int $code = NULL): int
    {
      if($code !== NULL && array_key_exists($code, self::$phrases))
       {
        $this->status = $code;
        if(!$this->reason)
          $this->reason = self::$phrases[$code];
       }
      return $this->status;
    }
   
    public function redirect($url, $permanently = false): void
    {
      if($permanently)
        $this->status(self::MOVED_PERMANENTLY);
      $this->header('Location', $url);
    }
 
    public function apply(): void
    {
      http_response_code($this->status());
      if($this->contentType)
        $this->header('Content-type', $this->contentType);
      foreach($this->headers as $name => $value)
        header("{$name}: {$value}");
      if($this->body)
        echo $this->body;
    }
 }