<?php
 
 namespace glx\HTTP\Server;
 
 use glx\Common;
 use glx\HTTP;

 class Request extends HTTP\Request implements I\Request
 {
    protected Common\I\Collection $get;
    protected Common\I\Collection $post;
    protected Common\I\Collection $cookie;
    protected Common\I\Collection $server;
    protected Common\I\Collection $files;
    protected Common\I\Collection $request;
    protected I\Client $client;
  
    public function __construct(array $params)
    {
      $this->get = new Common\ReadOnly($array = $params['get'] ?? $_GET);
      $this->post = new Common\ReadOnly($array = $params['post'] ?? $_POST);
      $this->server = new Common\ReadOnly($array = $params['server'] ?? $_SERVER);
      $this->files = new Common\ReadOnly($array = $params['files'] ?? $_FILES);
      $this->request = new Common\ReadOnly($array = $params['request'] ?? $_REQUEST);
      $this->cookie = $params['cookie'] instanceof Common\I\Collection ? $params['cookie'] : new Common\ReadOnly($array = $params['cookie'] ?? $_COOKIE);
      $this->client = new Client($this);
      parent::__construct($params);
    }
 
    public function post(string $name = NULL)
    {
      if($name !== NULL)
        return $this->post[$name];
      return $this->post;
    }
   
    public function cookie(string $name = NULL)
    {
      if($name !== NULL)
        return $this->cookie[$name];
      return $this->cookie;
    }
   
    public function get(string $name = NULL)
    {
      if($name !== NULL)
        return $this->get[$name];
      return $this->get;
    }
 
    public function server(string $name = NULL)
    {
      if($name !== NULL)
        return $this->server[$name];
      return $this->server;
    }

    public function files(string $name = NULL)
    {
      if($name !== NULL)
        return $this->files[$name];
      return $this->files;
    }

    public function input(string $name = NULL)
    {
      if($name !== NULL)
        return $this->request[$name];
      return $this->request;
    }
 
    public function body(): string
    {
      return file_get_contents('php://input');
    }

    public function client(): I\Client
    {
      return $this->client;
    }
    public function secure(): bool
    {
      return $this->uri->scheme() === 'https';
    }
 }