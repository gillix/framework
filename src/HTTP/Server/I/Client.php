<?php
    
    namespace glx\HTTP\Server\I;
    
    
    use DateTime;

    interface Client
    {
        public function ip(): string;
        
        public function agent(): string;
        
        public function country(): string;
        
        public function city(): string;
        
        public function time(): DateTime;
        
        public function timezone(): string;
    }