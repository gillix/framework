<?php
    
    namespace glx\Session\ID;
    
    class Custom extends Provider
    {
        public function __construct(string $id = null)
        {
            $this->id = $id ?? $this->create();
        }
        
        public function id(): string
        {
            return $this->id;
        }
        
        public function exist(): bool
        {
            return $this->id !== null;
        }
        
        public function create(int $lifetime = 0, array $options = []): string
        {
            return $this->id = $this->generate();
        }
        
        public function delete(): void { }
    }