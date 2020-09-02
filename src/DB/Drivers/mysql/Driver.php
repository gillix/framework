<?php
    
    namespace glx\DB\Drivers\mysql;
    
    use glx\DB;
    use PDO;

    class Driver extends DB\pdoDriver
    {
        protected DB\I\QueryCompiler $compiler;
        protected const DEFAULT_CHARSET = 'UTF8';
        
        public function compiler(): DB\I\QueryCompiler
        {
            if (!isset($this->compiler)) {
                $this->compiler = new QueryCompiler();
            }
            
            return $this->compiler;
        }
        
        protected static function makeURL(array $options): string
        {
            $params = [];
            if ($options['socket']) {
                $params['unix_socket'] = $options['socket'];
            } else {
                $params['host'] = $options['host'] ?? 'localhost';
                if ($options['post']) {
                    $params['port'] = $options['port'];
                }
            }
            if ($options['database']) {
                $params['dbname'] = $options['database'];
            }
            $params['charset'] = $options['charset'] ?? self::DEFAULT_CHARSET;
            array_walk($params, fn(&$item, $key) => $item = "$key=$item");
            $params = implode(';', $params);
            
            return "{$options['driver']}:{$params}";
        }
        
        protected static function attributes(array $options): array
        {
            $attributes = [];
            $init = [];
            if ($options['charset'] && (version_compare(PHP_VERSION, '5.3.6') < 0 || $options['collation'])) {
                $init[] = "SET NAMES {$options['charset']}" . ($options['collation'] ? " COLLATE {$options['collation']}" : '');
            }
            if ($options['timezone']) {
                $init[] = "SET time_zone='{$options['timezone']}'";
            }
            if ($options['strict']) {
                $modes = explode(',', 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION');
                $options['modes'] = is_array($options['modes']) ? array_merge($options['modes'], array_diff($modes, $options['modes'])) : $modes;
            }
            if ($options['modes']) {
                $modes = implode(',', $options['modes']);
                $init[] = "SET SESSION sql_mode='{$modes}'";
            }
            
            if (count($init)) {
                $attributes[PDO::MYSQL_ATTR_INIT_COMMAND] = implode('; ', $init);
            }
            
            return $attributes;
        }
    }
    
    DB\Connection::registerDriver('mysql', Driver::class);
 