<?php
 namespace glx\HTTP;

 use glx\Common;
 use glx\core;
 
 class REST extends Launcher
 {
    public function process($target)
    {
      // TODO: parse input and put to call as params
      /** @var core\Node $target */
      $index = $this->config->index;
      if($index instanceof Common\I\ObjectAccess)
        $index = $index[$this->server->request()->method()] ?? $index->default;
      try { return json_encode($result = $target->call($index ?? self::DEFAULT_INDEX), JSON_THROW_ON_ERROR); }
      catch(\JsonException $e) { $this->context->log()->error('Failed of encode REST response.', [$result]); }
      return '{}';
    }
 }