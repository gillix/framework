<?php
 namespace glx\HTTP;
 
 use glx\Common\I\Collection;
 use glx\Common\Stopwatch;
 use glx\core;
 use glx\Locale;

 class Launcher extends \glx\Launcher implements I\Launcher
 {
    protected I\Server $server;
    protected \glx\I\Context $context;
    protected const DEFAULT_INDEX = 'start';
    
    public function __construct($config)
    {
      parent::__construct($config);
      $this->server = new Server();
    }
 
    protected function getContextOptions(): array
    {
      if(!is_string($locale = $this->config->locale) && $locale->auto)
        $locale = $this->detectLocale() ?? $locale->default;
      if(!is_string($locale) && !$locale instanceof \glx\I\Locale)
        $locale = NULL;
      return [
        'locale'  => $locale,
        'profile' => $this->configSection('profile'),
        'cache'   => $this->configSection('cache'),
        'logger'  => $this->configSection('logger'),
        'http'    => $this->server,
        'input'   => $this->server->request()->input(),
        'config'  => $this->config
      ];
    }
  
    private function configSection(string $section)
    {
      $section = $this->config[$section];
      return $section instanceof Collection ? $section->array() : $section;
    }
  
    public function server(): Server
    {
      return $this->server;
    }
   
    protected function fetch(string $path)
    {
      return $this->storage->root()->get($path);
    }
   
    protected function process($target)
    {
      /** @var core\Node $target */
      return $target->call($this->config->index ?? self::DEFAULT_INDEX);
    }
   
    public function execute(\glx\I\Context $context, string $path = NULL)
    {
      $stopwatch = Stopwatch::start();
      /** @var core\Node $target */
      $target = $this->fetch($path ?? $this->server->request()->target());
      try
       {
        if($target && $target->is('NODE'))
          $this->server->response()->body($this->process($target));
        else
          throw new Error(Response::NOT_FOUND);
       }
      catch(Error $e)
       {
        $this->server->response()->status($e->getCode());
        if($this->config->error && ($uri = $this->config->error[(string)$e->getCode()]))
          return $this->redirect(new URI($uri), Redirect::INTERNAL) ?? $stopwatch;
       }
      catch(Status $e)
       {
        $this->server->response()->status($e->getCode());
       }
      catch(Redirect $e)
       {
        $uri = $e->uri();
        $mode = $e->mode();
        return $this->redirect($uri, $mode) ?? $stopwatch;
       }
      catch(\glx\Exception $e)
       {
        $this->server->response()->body($e->out());
        $this->server->send();
        throw $e;
       }
      $stopwatch->tick('execute');
      $this->server->send();
      $stopwatch->tick('send');
      $this->context->log('stat')->info('Launcher timing', $stopwatch->stat());
      return $stopwatch;
    }
  
    protected function redirect(I\URI $uri, int $mode)
    {
      if($mode === Redirect::AUTO)
        $mode = $uri->has('scheme') ? Redirect::EXTERNAL : Redirect::INTERNAL;
      if($mode === Redirect::EXTERNAL)
       {
        $this->server->response()->redirect($uri);
        $this->server->send();
       }
      else
        return $this->run($uri->path());
      return NULL;
    }
  
    protected function detectLocale(): ?\glx\I\Locale
    {
      $header = $this->server->request()->header('Accept-Language');
      $locales = [];
      array_map(static function($i) use (&$locales) {
          $r = explode(';', $i);
          $locales[$r[1] ? explode('=', $r[1])[1] : 1] = $r[0] ;
      }, explode(',', $header));
      $locale = NULL;
      if($locales)
       {
        krsort($locales);
        foreach($locales as $locale)
          if(Locale::valid($locale))
            break;
       }
      if($locale && !strpos($locale, '-') && ($country = $this->server->request()->client()->country()) && Locale::valid($locale, $country))
        $locale .= '-'.$country;
      if(!$locale && $country = $this->server->request()->client()->country())
        return Locale::for($country);
      elseif($locale)
        return Locale::get($locale);
      return NULL;
    }
 }