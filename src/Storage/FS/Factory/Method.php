<?php
    
    namespace glx\Storage\FS\Factory;
    
    use glx\core;
    use glx\Storage;

    class Method extends Storage\FS\Factory
    {
        protected static $header = <<<'header'
<?php
namespace glx;

header;
        
        protected static $codeTemplate = <<<'code'
return function([arguments]) use($context) {
?>[code]
<?};
code;
        
        public static function probe(array $info, Storage\FS\I\Structure $current): bool
        {
            if (in_array($info['extension'], ['method', 'mtd', 'tpl', 'template', 'php'])) {
                return true;
            }
            
            return false;
        }
        
        public static function create(array $info, Storage\FS\I\Structure $current, Storage\FS\I\Storage $storage): array
        {
            $record['creator'] = self::class;
            
            $code = null;
            // loading code of method
            if ($info['content'] ?? false) {
                // if creates from parent .node definition
                $code = $info['content'];
//        $record['source'] = $info['source'];
            } elseif (isset($info['file']) && is_file($path = $info['path'])) {
                // if creates from file
                $code = file_get_contents($path);
                $record['source'] = $current->relative($info['file']);
            }
            
            if ($code === null) {
                throw new Storage\Exception('method code not loaded');
            }
            
            // convert code to native php
            if (($info['extension'] ?? '') !== 'php') {
                $arguments = null;
                // fetch arguments if we have it
                $code = preg_replace_callback('/\s*@\(([^)]*)\)/m', function ($found) use (&$arguments) {
                    $arguments = $found[1];
                    
                    return null;
                }, $code, 1);
                
                // detect closing tag
                if (substr_count($code, '<?') > substr_count($code, '?>')) {
                    $code .= '?>';
                }
                
                // make php code
                $code = str_replace(['[arguments]', '[code]'], [$arguments, $code], self::$codeTemplate);
                // add namespace directives
                $code = self::$header . $code;
            } else {
                $code = preg_replace('/<\?(php)?/', self::$header, $code);
            }
            
            
            // save to hidden section of compiler
            $hidden = $current->relative($info['name'] . '.php');
            $storage->compiler()->write($hidden, $code, 'hidden');
            $record['hidden'] = $hidden;
            
            // fetch options for new object
            $options = [
             'storage' => $storage,
             'source'  => new Storage\FS\Pathfinder($storage->id(), $hidden, 'hidden')
            ];
            if (($old = $info['old'] ?? false) && $old instanceof core\I\Entity) {
                $options['id'] = $old->id();
            }
            
            // create method object
            $method = new core\Method($options);
            $record['object'] = $method;
            $record['time'] = time(); // может быть другой формат
            
            return $record;
        }
        
        public static function clear(array $record, Storage\FS\I\Storage $storage): void
        {
            $storage->compiler()->delete($record['hidden'], 'hidden');
            parent::clear($record, $storage);
        }
    }
    
    Storage\FS\Storage::factory(['method', 'mtd', 'template', 'tpl', 'php'], Method::class);
