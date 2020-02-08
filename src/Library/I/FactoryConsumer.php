<?php
 
 namespace glx\Library\I;
 
 interface FactoryConsumer
 {
    public function factory(Factory $factory = NULL): Factory;
 }