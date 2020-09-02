<?php
    
    namespace glx\DB\I;
    
    
    use glx\DB\E\ConnectionFailed;
    use glx\DB\E\QueryPerformingFailed;

    interface Connection
    {
        /**
         * @return mixed
         * @throws ConnectionFailed
         */
        public function connect();
        
        public function disconnect();
        
        public function connected(): bool;
        
        /**
         * @param $query
         * @param array|null $values
         * @param null $fetch
         * @return mixed
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function query($query, ?array $values = null, $fetch = null);
        
        /**
         * @param $query
         * @param array|null $values
         * @return mixed
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function execute($query, ?array $values = null);

//    public function prepare($query);
        public function lastID();
    }