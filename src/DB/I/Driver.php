<?php
    
    namespace glx\DB\I;
    
    
    use Closure;
    use glx\DB\E\QueryPerformingFailed;

    interface Driver extends Connection
    {
        /**
         * @param Closure $execute
         * @param $query
         * @param array|null $values
         * @return mixed
         * @throws QueryPerformingFailed
         */
        public function perform(Closure $execute, $query, ?array $values = null);
        
        public function compiler(): QueryCompiler;
    }