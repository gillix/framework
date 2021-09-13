<?php
    
    namespace glx\DB\E;
    
    use glx\DB\Exception;
    use Throwable;

    class QueryPerformingFailed extends Exception
    {
        protected string $query;
        protected array $values;
        
        public function __construct(string $query, array $values, $message = '', $code = 0, Throwable $previous = null, string $content = null)
        {
            parent::__construct($message, $code, $previous, $content);
            $this->query = $query;
            $this->values = $values;
        }
        
        public function query(): string
        {
            return $this->query;
        }
        
        public function values(): array
        {
            return $this->values;
        }
    }