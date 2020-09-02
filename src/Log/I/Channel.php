<?php
    
    namespace glx\Log\I;
    
    use Psr\Log\LoggerInterface;

    interface Channel extends LoggerInterface
    {
        public function name(): string;
        
        public function debug($message, array $context = []): self;
        
        public function info($message, array $context = []): self;
        
        public function notice($message, array $context = []): self;
        
        public function warning($message, array $context = []): self;
        
        public function error($message, array $context = []): self;
        
        public function critical($message, array $context = []): self;
        
        public function alert($message, array $context = []): self;
        
        public function emergency($message, array $context = []): self;
        
        public function log($level, $message, array $context = []): self;
    }
