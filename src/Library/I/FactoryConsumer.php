<?php
    
    namespace glx\Library\I;
    
    interface FactoryConsumer
    {
        public function factory(Factory|null $factory = null): Factory;
    }
