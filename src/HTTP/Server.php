<?php
 namespace glx\HTTP;
 
 
 class Server implements I\Server
 {
    protected Server\I\Request $request;
    protected Server\I\Response $response;
    protected I\Cookie $cookie;
  
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
   
    public function send(): void
    {
      $this->cookie->apply();
      $this->response->apply();
    }
 }