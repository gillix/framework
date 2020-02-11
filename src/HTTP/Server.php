<?php
 namespace glx\HTTP;
 
 use glx\Context;
 use glx\Session;
 
 class Server implements I\Server
 {
    protected Server\I\Request $request;
    protected Server\I\Response $response;
    protected I\Cookie $cookie;
    protected array $session;
  
    public function __construct()
    {
      $this->cookie = new Cookie($_COOKIE);
      $this->request = new Server\Request([
          'method' => $_SERVER['REQUEST_METHOD'],
          'version' => explode('/', $_SERVER['SERVER_PROTOCOL'])[1],
          'uri' => [
             'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
             'scheme' => $_SERVER['REQUEST_SCHEME'],
             'host' => $_SERVER['HTTP_HOST'],
             'port' => $_SERVER['SERVER_PORT'],
             'query' => $_SERVER['QUERY_STRING'],
           ],
          'headers' => getallheaders(),
          'cookie' => $this->cookie,
      ]);
      $this->response = new Server\Response();
    }
 
    public function cookie(): I\Cookie
    {
      return $this->cookie;
    }
   
    public function request(): Server\I\Request
    {
      return $this->request;
    }
   
    public function response(): Server\I\Response
    {
      return $this->response;
    }
   
    public function session(string $channel = NULL, int $lifetime = 0): Session\I\Session
    {
      if(!isset($this->session))
        $this->session = [];
      if(!isset($this->session[$channel]))
       {
        if($options = Context::config()->session)
          $options = $options->array();
        $options ??= [];
        if($lifetime)
          $options['lifetime'] = $lifetime;
        $this->session[$channel] = new Session\Session(new Session\ID\Cookie($channel, $this->cookie, $options['secure'] ?? true), $options);
       }
      return $this->session[$channel];
    }
   
    public function send(): void
    {
      $this->cookie->apply();
      $this->response->apply();
    }
 }