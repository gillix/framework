<?php
 namespace glx\Events;
 

 class Manager implements I\Support, I\Provider
 {
    use Support;
    
    protected array $emitters = [];
    
    public function for(I\Emitter $emitter): I\Support
    {
      if(!($dispatcher = $this->emitters[spl_object_hash($emitter)]))
        $this->emitters[spl_object_hash($emitter)] = $dispatcher = new class() implements I\Support { use Support; };
      return $dispatcher;
    }
 }