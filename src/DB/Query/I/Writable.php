<?php
    
    namespace glx\DB\Query\I;
    
    
    use glx\DB\E\ConnectionFailed;
    use glx\DB\E\QueryPerformingFailed;

    interface Writable extends Query
    {
        /**
         * @return int
         * @throws ConnectionFailed
         * @throws QueryPerformingFailed
         */
        public function perform(): int;
    }