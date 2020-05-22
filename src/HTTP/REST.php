<?php
 namespace glx\HTTP;

 use glx\Common;
 use glx\core;
 
 class REST extends Launcher
 {
    public function process($target)
    {
      /** @var core\Node $target */

       // parse json input and put to call as params
      try
       {
        $request = (array)json_decode($this->context->http()->request()->body(), true, 512, JSON_THROW_ON_ERROR);
        $this->context->input()->link(new Common\Collection($request));
       }
      catch(\JsonException $e) { $request = null; }

      // index by request type
      $index = $this->config->index;
      if($index instanceof Common\I\Collection)
        $index = $index[$this->server->request()->method()] ?? $index->default;
      try { return json_encode($result = $target->call($index ?? self::DEFAULT_INDEX, $request), JSON_THROW_ON_ERROR); }
      catch(\JsonException $e) { $this->context->log()->error('Failed of encode REST response.', isset($result) ? [$result] : null); }
      return '{}';
    }
 }