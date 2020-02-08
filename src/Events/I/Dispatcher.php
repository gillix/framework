<?php
 namespace glx\Events\I;
 
 interface Dispatcher
 {
   /** Fire the event
    * @param string $event
    * @param array|NULL $arguments
    */
    public function fire(string $event, array $arguments = NULL): void;

   /** Process event
    * @param Event $event
    */
    public function dispatch(Event $event): void;

    public function on(string $event, \Closure $handler): void;
    public function off(string $event, \Closure $handler): void;
 }
