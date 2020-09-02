<?php
    
    namespace glx\Storage\FS\Factory;
    
    use Exception;

    class RecordChanged extends Exception
    {
        private $record;
        
        public function __construct(array $record)
        {
            $this->record = $record;
        }
        
        public function record(): array
        {
            return $this->record;
        }
    }