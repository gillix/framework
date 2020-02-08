<?php

 namespace glx\core\E;
 
 use glx\Context;
 use glx\core;
 
 class PropertyAccessViolation extends core\Exception
 {
    protected static $msg = " property access prohibited for ";
    
    public function __construct(core\I\Joint $property, $code = 0, \Throwable $previous = NULL)
    {
      $callstack = Context::get()->callstack();
      if($callstack->empty()) $initiator = 'global space';
      else $initiator = $callstack->current()->parent()->location();
      parent::__construct("{$property->location()}: " . core\VisibilityLevels::convert($property->visibility()) . self::$msg . $initiator, $code, $previous);
    }
 
 }
