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
      $request = null;
      if($requestBody = $this->context->http()->request()->body())
        try
         {
          $request = (array)json_decode($requestBody, true, 512, JSON_THROW_ON_ERROR);
          $this->context->input()->link(new Common\Collection($request));
         }
        catch(\JsonException $e) {}

      // index by request type
      $index = $this->config->index;
      if($index instanceof Common\I\Collection)
        $index = $index[$this->server->request()->method()] ?? $index->default;
      try
       {
        return json_encode($result = $target->call(
            $index ?? self::DEFAULT_INDEX,
            $request ?? $this->context->input()->array()
        ), JSON_THROW_ON_ERROR);
       }
      catch(RestError $e) { return json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR); }
      catch(\JsonException $e) { $this->context->log()->error('Failed of encode REST response.', isset($result) ? [$result] : null); }
      return '{}';
    }
 }