<?php
    
    namespace glx\DB\Query\I;
    
    
    use glx\DB\E\ConnectionFailed;
    use glx\DB\E\QueryPerformingFailed;

    interface Fetching
    {
        /**
         * @param null $callback
         * @return Result
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function get($callback = null): Result;
        
        /**
         * @return Result
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function one(): Result;
        
        /**
         * @param null $column
         * @return mixed
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function value($column = null);
        
        /**
         * @param array $columns
         * @param $page
         * @param null $pp
         * @return Aggregated
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function aggregated(array $columns, $page, $pp = null): Aggregated;
        
        /**
         * @param $page
         * @param null $pp
         * @return Paginated
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function page($page, $pp = null): Paginated;
        
        /**
         * @param null $index
         * @return Result
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function column($index = null): Result;
        
        /**
         * @param null $class
         * @param null $args
         * @return mixed
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function object($class = null, $args = null);
        // TODO: + key pair
    }