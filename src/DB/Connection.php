<?php
 
 namespace glx\DB;
 
 use glx\DB\Query;

 class Connection implements I\Connection, I\Queryable
 {
    protected I\Driver $driver;
    protected static array $drivers = [];
    protected static string $defaultDriver = 'mysql';
    
    public function __construct(array $options)
    {
      // TODO: add ability to specify connection url
      // TODO: add ability to specify write connection separately

      $driver = $options['driver'] ??= self::$defaultDriver;
      if(!self::loadDrivers() || !($driver = self::$drivers[$driver]) || !class_exists($driver))
        throw new Exception("Can`t load DB driver {$options['driver']}");
      $this->driver = new $driver($options);
    }
 
    public function connect(): void
    {
      $this->driver->connect();
    }
    
    public function disconnect(): void
    {
      $this->driver->disconnect();
    }
    
    public function connected(): bool
    {
      return $this->driver->connected();
    }
    
    public function query($query, ?array $values = NULL, $fetch = NULL)
    {
      return $this->driver->query($query, $values, $fetch);
    }
    
    public function execute($query, ?array $values = NULL)
    {
      return $this->driver->execute($query, $values);
    }
    
    public function lastID(): string
    {
      return $this->driver->lastID();
    }
    
    public function table($table, $alias = NULL): Query\I\Table
    {
      return new Query\Table($this->driver, $table, $alias);
    }
    
    public function from($table, $alias = NULL): Query\I\SearchableTable
    {
      return new Query\SearchableTable($this->driver, $table, $alias);
    }

    public function update(string $table = NULL, $where = NULL, array $fields = NULL): Query\I\Update
    {
      $query = new Query\Update($this->driver);
      if($table)  $query->table($table);
      if($where)  $query->where($where);
      if($fields) $query->set($fields);
      return $query;
    }
    
    public function select(...$columns): Query\I\Select
    {
      $query = new Query\Select($this->driver);
      if($columns) $query->select(...$columns);
      return $query;
    }
    
    public function insert(string $into = NULL, $fields = NULL): Query\I\Insert
    {
      $query = new Query\Insert($this->driver);
      if($into)   $query->into($into);
      if($fields) $query->set($fields);
      return $query;
    }
    
    public function delete(string $table = NULL, $where = NULL): Query\I\Delete
    {
      $query = new Query\Delete($this->driver);
      if($table) $query->from($table);
      if($where) $query->where($where);
      return $query;
    }
  
    private static function loadDrivers()
    {
      if(!count(self::$drivers))
        foreach(new \DirectoryIterator(__DIR__.'/Drivers') as $driver)
         {
          if(in_array($driver->getFilename(), ['.', '..']) || !$driver->isDir() || !is_file($include = $driver->getPathname().DIRECTORY_SEPARATOR.'Driver.php')) continue;
          include_once $include;
         }
      return (bool)count(self::$drivers);
    }
  
    public static function registerDriver(string $name, string $class)
    {
      self::$drivers[$name] = $class;
    }
 
 }