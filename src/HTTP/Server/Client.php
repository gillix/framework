<?php
 namespace glx\HTTP\Server;
 
 
 class Client implements I\Client
 {
    protected I\Request $request;
    protected $geo;
  
    public function __construct(I\Request $request)
    {
      $this->request = $request;
    }
 
    public function ip(): string
    {
     return $this->request->server('REMOTE_ADDR');
    }
   
    public function agent(): string
    {
      return $this->request->server('HTTP_USER_AGENT');
    }
   
    public function country(): string
    {
      return $this->geo()->country->isoCode ?? '';
    }
   
    public function city(): string
    {
      return $this->geo()->city->name ?? '';
    }
   
    public function time(): \DateTime
    {
      try { return new \DateTime('now', new \DateTimeZone($this->timezone())); }
      catch(\Exception $e) { return new \DateTime(); }
    }
   
    public function timezone(): string
    {
      return $this->geo()->location->timeZone ?? '';
    }
  
    protected function geo()
    {
      if(!isset($this->geo))
       {
        $factory = new \glx\Library\Factory();
        if($geo = $factory->get('geoip'))
          try { $this->geo = $geo->city($this->ip()); }
          catch(\Exception $e) {}
        else
          throw new \glx\Exception('Please setup GeoIp2 as described in documentation');
       }
      return $this->geo;
    }
 }