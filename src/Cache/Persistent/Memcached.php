<?php
    
    namespace glx\Cache\Persistent;
    
    use ErrorException;
    use glx\Cache;
    use Symfony\Component\Cache\Adapter\MemcachedAdapter;
    use Symfony\Component\Cache\Exception\CacheException;
    use Symfony\Component\Cache\Psr16Cache;

    class Memcached extends Cache\SymfonyCache
    {
        public function __construct(array $options = [])
        {
            if (!class_exists('\Memcached')) {
                throw new Cache\E\NotAvailable('Memcached support is not found. Please install "memcached" extension');
            }
            try {
                parent::__construct(new Psr16Cache(new MemcachedAdapter(MemcachedAdapter::createConnection($options['dns'] ?? 'memcached://localhost', $options['options'] ?? []))));
            } catch (CacheException $e) {
                $error = $e->getMessage();
            } catch (ErrorException $e) {
                $error = $e->getMessage();
            }
            if (isset($error)) {
                throw new Cache\E\NotAvailable('Memcached initialization failed: ' . $error);
            }
        }
    }
 