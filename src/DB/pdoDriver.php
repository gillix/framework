<?php
    
    namespace glx\DB;
    
    use Closure;
    use glx\Common\Stopwatch;
    use glx\DB;
    use glx\DB\Query\Query;
    use PDO;
    use PDOException;
    use PDOStatement;
    use Psr\Log\LoggerInterface;
    use Psr\Log\NullLogger;

    abstract class pdoDriver implements I\Driver
    {
        protected PDO             $pdo;
        protected array           $options;
        protected int             $fetchMode = PDO::FETCH_ASSOC;
        protected LoggerInterface $logger;
        
        protected static array $fetchModes = [
         'object' => PDO::FETCH_OBJ,
         'array'  => PDO::FETCH_ASSOC,
         'class'  => PDO::FETCH_CLASS,
         'column' => PDO::FETCH_COLUMN,
        ];
        protected static array $attributes = [
         PDO::ATTR_CASE              => PDO::CASE_NATURAL,
         PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
         PDO::ATTR_STRINGIFY_FETCHES => false,
         PDO::ATTR_EMULATE_PREPARES  => false,
        ];
        
        
        public function __construct(array $options)
        {
            $this->options = $options;
            if ($options['fetch']) {
                $this->fetchMode = self::$fetchModes[$options['fetch']] ?? $this->fetchMode;
            }
            if ($options['logger'] instanceof LoggerInterface) {
                $this->logger = $options['logger'];
            } else {
                $this->logger = new NullLogger();
            }
        }
        
        /**
         * @param false $force
         * @throws E\ConnectionFailed
         */
        public function connect($force = false): void
        {
            if ($force || !$this->connected()) {
                try {
                    $this->pdo = new PDO(
                     static::makeURL($this->options),
                     $this->options['username'],
                     $this->options['password'],
                     static::attributes($this->options) + static::$attributes
                    );
                } catch (PDOException $e) {
                    // TODO: detect if lost connection and reconnect
                    throw new E\ConnectionFailed('DB connection failed', 0, $e);
                }
            }
        }
        
        abstract protected static function makeURL(array $options): string;
        
        abstract protected static function attributes(array $options): array;
        
        public function connected(): bool
        {
            return isset($this->pdo);
        }
        
        public function disconnect(): void
        {
            if ($this->connected()) {
                unset($this->pdo);
            }
        }
        
        /**
         * @param $query
         * @return PDOStatement
         * @throws E\ConnectionFailed
         */
        public function prepare($query): PDOStatement
        {
            $this->connect();
            
            return $this->pdo->prepare($query);
        }
        
        /**
         * @param $query
         * @param array|null $values
         * @return mixed
         * @throws E\ConnectionFailed
         * @throws E\QueryPerformingFailed
         */
        public function execute($query, ?array $values = null)
        {
            return $this->perform(function ($query, $values) {
                
                $stmt = $this->prepare($query);
                
                if ($values) {
                    static::bind($stmt, $values);
                }
                
                $stmt->execute();
                
                return $stmt->rowCount();
            }, $query, $values);
        }
        
        /**
         * @param $query
         * @param array|null $values
         * @param null $fetch
         * @return mixed
         * @throws E\ConnectionFailed
         * @throws E\QueryPerformingFailed
         */
        public function query($query, ?array $values = null, $fetch = null)
        {
            return $this->perform(function ($query, $values) use ($fetch) {
                
                $stmt = $this->prepare($query);
                
                if ($values) {
                    static::bind($stmt, $values);
                }
                
                $stmt->execute();
                
                $fetch = (array)$fetch;
                $fetch[0] ??= $this->fetchMode;
                
                return $stmt->fetchAll(...$fetch);
            }, $query, $values);
        }
        
        /**
         * using common method for raising event and other common things
         * @param Closure $execute
         * @param Query | string $query
         * @param null | array $values
         * @return mixed
         * @throws E\ConnectionFailed|E\QueryPerformingFailed
         */
        public function perform(Closure $execute, $query, ?array $values = null)
        {
            $this->connect();
            if ($query instanceof DB\Query\I\Query) {
                [$query, $values] = $query->compile();
            }
            $stopwatch = Stopwatch::start();
            try {
                $result = $execute($query, $values);
            } catch (PDOException $e) {
                $this->logger->error($e->getMessage(), [
                 'query'  => $query,
                 'values' => $values,
                 'file'   => $e->getFile(),
                 'line'   => $e->getLine(),
                 'trace'  => $e->getTrace()
                ]);
                throw new DB\E\QueryPerformingFailed($query, $query, $e->getMessage(), $e->getCode(), $e);
            }
            $time = $stopwatch->elapsed()->get();
            $this->logger->debug((string)$query, compact('values', 'time'));
            
            return $result;
        }
        
        public static function bind(PDOStatement $stmt, array $values): void
        {
            foreach ($values as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }
        
        /**
         * @return string
         * @throws Exception
         */
        public function lastID(): string
        {
            if ($this->connected()) {
                return $this->pdo->lastInsertId();
            }
            throw new Exception('Can`t fetch last inserted ID if not connected to DB');
        }
    }
 