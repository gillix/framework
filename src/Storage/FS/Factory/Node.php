<?php
    
    namespace glx\Storage\FS\Factory;
    
    use DirectoryIterator;
    use glx\Config;
    use glx\Context;
    use glx\core;
    use glx\Storage;

    class Node extends Storage\FS\Factory
    {
        public static function probe(array $info, Storage\FS\I\Structure $current): bool
        {
            return (!$info['extension'] && $info['content'] && is_array(
                        $info['content']
                    )) || ($info['file'] && $info['extension'] === 'node' && $info['path'] && is_file(
                        $info['path']
                    )) || ($info['path'] && is_dir($info['path']));
        }
        
        public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
        {
            $definitions = [];
            $record['creator'] = self::class;
            
            // loading node definition
            if (isset($info['content'])) {
                // if creates from parent .node definition
                $definitions = $info['content'];
//        $record['source'] = $info['source'];
            } else {
                // if creates from file definition
                if (isset($info['file']) && is_file($path = $info['path'])) {
                    $record['source'] = $current->relative($info['file']);
                } // if create form filesystem folder
                elseif (is_dir($current->path())) {
                    if (!is_file($path = $current->path('.node'))) {
                        $path = null;
                      //  $definitions = [];
                    }
                    $record['source'] = $current->relative();
                } else {
                    $path = null;
                }
                if ($path) {
                    $definitions = Config\Reader::get()->parse(file_get_contents($path));
                }
            }
            
            // class of new object
            $class = core\Node::class;
            $instance = $definitions['instance'] ?? $definitions['class'] ?? $definitions['prototype'] ?? '';
            if (class_exists($instance)) {
                $class = $instance;
                unset(
                 $definitions['instance'],
                 $definitions['class'],
                 $definitions['prototype']
                );
            }
            
            // fetching node options
            $options = ['storage' => $storage];
            if (($old = $info['old'] ?? null) && $old instanceof core\I\Entity) {
                $options['id'] = $old->id();
            }
            $reserved = $class::reserved();
            foreach ($definitions as $name => $value) {
                if (in_array($name, $reserved, true)) {
                    $options[$name] = $value;
                    unset($definitions[$name]);
                    continue;
                }
            }
            
            // create node object
            /** @var core\Node $node */
            $node = new $class($options);
            $record['object'] = $node;
            $record['time'] = time(); // может быть другой формат
            
            // add children from definitions
            foreach ($definitions as $name => $value) {
                $item = self::fetchName($name);
                $item['content'] = $value;
// не нужен        $item['source'] = $info['source'] ?? $current->relative('.node');
                $child = $storage->produce($item, $current);
                if ($child) {
                    $record['depends'][] = $child['object']->id()->object();
                    
                    // put loader object instead of original one to avoid full loading of objects tree on each request
                    [$name, $profile] = explode('@', $item['name'], 2);
                    $profile ??= Context::DEFAULT_PROFILE;
                    $binder = new core\Binder($name, $child['object'], $item['visibility'], $profile);
                    $loader = new Storage\Loader($binder);
                    $node->add($binder);
                }
            }
            
            if ($old) {
                // move rest entries from old object
                $old->each(function () use ($storage, $node) {
                    try {
                        $child = $storage->fetch($this->origin()->id()->object());
                    } catch (Storage\Exception $e) {
                        return;
                    }
                    if (isset($child['source'])) {
                        $node->add($this);
                    }
                });
            } elseif (!isset($info['content']) && !isset($info['file'])) // add children from filesystem directory
            {
                foreach (new DirectoryIterator($current->path()) as $file) {
                    if (in_array($file->getFilename(), ['.', '..', '.node'])) {
                        continue;
                    }
                    $fileName = $file->getFilename();
                    $item = self::fetchName($fileName);
                    if (!$file->isDir()) {
                        $item['file'] = $fileName;
                    }
                    $item['path'] = $current->path($fileName);
                    $child = $storage->produce($item, $file->isDir() ? $current->get($fileName) : $current);
                    
                    // put loader object instead of original one to avoid full loading of objects tree on each request
                    if ($child) {
                        [$name, $profile] = explode('@', $item['name'] ?? '', 2);
                        $profile ??= Context::DEFAULT_PROFILE;
                        $binder = new core\Binder($name, $child['object'], $item['visibility'], $profile);
                        new Storage\Loader($binder);
                        $node->add($binder);
                    }
                }
            }
            
            return $record;
        }
        
        public static function check(array $record, Storage\FS\I\Storage $storage): void
        {
            if (!isset($record['source'])) {
                return;
            }
            $src = $storage->structure()->source;
            $source = $src->path($record['source']);
            if (!is_dir($source) && !is_file($source)) // looks like deleted
            {
                throw new Storage\Exception('Trying to access non-existent node: ' . $record['source']);
            }
            $changed = false;
            
            // check if .node file changed
            if ((is_file($source) && self::fileChanged($source, $record['time']))
             || (is_file($def = $source . '/.node') && self::fileChanged($def, $record['time']))) {
                $changed = true;
                
                // kill dependent items because source is changed
                if (is_array($record['depends'] ?? null)) {
                    foreach ($record['depends'] as $id) {
                        $child = $storage->fetch($id);
                        if (isset($child['creator'])) {
                            $child['creator']::purge($child, $storage);
                        }
                    }
                }
                
                // create new node object with same ID
                $record = static::recreate($record, $storage);
            }
            
            // if folder is changed
            if (!is_file($source) && self::fileChanged($source, $record['time'])) {
                $changed = true;
                $record['time'] = time();
                // get folder entries
                $entries = scandir($source);
                $entries = array_filter($entries, static function ($entry) {
                    return !($entry === '.' || $entry === '..' || $entry === '.node');
                });
                
                $current = $src->get($record['source']);
                
                // check changes in folder entries
                $node = $record['object'] ?? null;
                $node->each(function () use ($storage, &$entries, $node, $src, $current) {
                    $child = $storage->fetch($this->origin()->id()->object());
                    if (isset($child['source'])) {
                        $exists = false;
                        array_walk($entries, function ($item, $i) use (&$exists, &$entries, $current, $child) {
                            if ($current->relative($item) === $child['source']) {
                                $exists = true;
                                unset($entries[$i]);
                            }
                        });
                        if (!$exists) // deleted
                        {
                            $node->remove($this);
                            $child['creator']::purge($child, $storage);
                        }
                    }
                });
                
                // create and add rest of entries because it's new
                foreach ($entries as $entry) {
                    // TODO: вынести отдельно: дубликат кода
                    $item = self::fetchName($entry);
                    $path = $current->path($entry);
                    $isDir = is_dir($path);
                    if (!$isDir) {
                        $item['file'] = $entry;
                    }
                    $item['path'] = $path;
                    $child = $storage->produce($item, $isDir ? $current->get($entry) : $current);
                    
                    // put loader object instead of original one to avoid full loading of objects tree on each request
                    if ($child) {
                        [$name, $profile] = explode('@', $item['name'], 2);
                        $profile ??= Context::DEFAULT_PROFILE;
                        $binder = new core\Binder($name, $child['object'] ?? null, $item['visibility'] ?? core\I\Visibility::PUBLIC, $profile);
                        new Storage\Loader($binder);
                        $node->add($binder);
                    }
                }
            }
            if ($changed) {
                throw new RecordChanged($record);
            }
        }
        
        public static function recreate(array $record, Storage\FS\I\Storage $storage): array
        {
            $source = $record['source'];
            $src = $storage->structure()->source;
            $path = $src->path($source);
            $parent = implode('/', explode('/', $source, -1));
            $file = str_replace($parent . '/', '', $source);
            $info = self::fetchName($file);
            if (is_file($path)) {
                $info['file'] = $file;
                $current = $src->get($parent);
            } else {
                $current = $src->get($source);
            }
            $info['path'] = $path;
            $info['old'] = $record['object'];
            
            return self::create($info, $current, $storage);
        }
        
        public static function purge(array $record, Storage\FS\I\Storage $storage): void
        {
            $record['object']->each(function () use ($storage) {
                $child = $storage->fetch($this->origin()->id()->object());
                $child['creator']::purge($child, $storage);
            });
            static::clear($record, $storage);
        }
    }
    
    Storage\FS\Storage::factory([Storage\FS\Storage::DEFAULT_FACTORY, 'node'], Node::class);
