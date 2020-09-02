<?php
    
    namespace glx\Cache\Persistent;
    
    use glx\Cache;
    use Symfony\Component\Cache\Adapter\RedisAdapter;
    use Symfony\Component\Cache\Psr16Cache;

    class Redis extends Cache\SymfonyCache
    {
        public function __construct(array $options = [])
        {
            if (!class_exists('\Redis') && !class_exists('\RedisArray') && !class_exists('\RedisCluster') && !class_exists('\Predis')) {
                throw new Cache\E\NotAvailable('Redis support is not found. Please install "php-redis" extension or Predis library');
            }
            
            parent::__construct(new Psr16Cache(new RedisAdapter(RedisAdapter::createConnection($options['dns'] ?? 'redis://localhost', $options['options'] ?? []))));
        }
    }
 