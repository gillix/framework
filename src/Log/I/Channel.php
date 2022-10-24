<?php
    
    namespace glx\Log\I;
    
    use Psr\Log\LoggerInterface;

    interface Channel extends LoggerInterface
    {
        public function name(): string;
        
    }
