<?php
    
    namespace glx\Session\sql;
    
    use glx\Context;
    use glx\DB;
    use glx\Exception;
    use glx\Library\Components;
    use glx\Session;

    class Storage implements Session\I\Storage
    {
        protected DB\I\Queryable $db;
        protected string         $table;
        protected const DEFAULT_LIFETIME = 24 * 60 * 60;
        protected const DEFAULT_TABLE    = 'session.session';
        
        public function __construct(array $options = [])
        {
            $this->db = $options['db'] ?? Components::get('db');
            if (!$this->db) {
                throw new Exception('Cant initialise sql session storage: DB connection is not configured');
            }
            $this->table = $options['table'] ?? self::DEFAULT_TABLE;
        }
        
        public function read(string $id): array
        {
            return unserialize($this->db->from($this->table)->where('id', $id)->value('data')) ?: [];
        }
        
        public function write(string $id, array $data, int $lifetime = null): void
        {
            $this->db->insert($this->table, [
             'id'       => $id,
             'data'     => serialize($data),
             'lifetime' => $lifetime ?? Context::config()->session->lifetime ?? self::DEFAULT_LIFETIME,
            ])->orUpdate()->perform();
        }
        
        public function delete($id): void
        {
            $this->db->from($this->table)->delete(['id', $id]);
        }
        
        public function exist($id): bool
        {
            return (bool)$this->db->from($this->table)->where('id', $id)->value('COUNT(*)');
        }
        
        public function clear(int $lifetime): void
        {
            $this->db->from($this->table)->where('UNIX_TIMESTAMP(created) - UNIX_TIMESTAMP(NOW())', '>', $lifetime);
        }
        
        public function relocate(string $old, string $new): void
        {
            $this->db->table($this->table)->where('id', $old)->update('id', $new);
        }
    }
