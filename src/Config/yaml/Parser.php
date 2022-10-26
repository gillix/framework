<?php
    
    namespace glx\Config\yaml;
    
    use glx\Config;
    use glx\Exception;
    use Symfony\Component\Yaml\Exception\ParseException;
    use Symfony\Component\Yaml\Yaml;

    require_once __DIR__ . '/../I/Parser.php';
    
    class Parser implements Config\I\Parser
    {
        public static function parse(string $content, array $callbacks = null): array
        {
            if (extension_loaded('yaml')) {
                return yaml_parse($content);
            }
            try {
                return Yaml::parse($content);
            } catch (ParseException $e) {
            }
            throw new Exception('Need to load module "yaml" for parsing yaml content');
            
        }

        protected function fetchDirectives(array $directives, array $data): array
        {
            $walker = static function (&$item, $key) use ($directives, &$walker) {
                if (isset($directives[$key])) {
                    $callback = $directives[$key];
                    if (is_callable($callback)) {
                        $item = $callback($item);
                    }
                } elseif (is_array($item)) {
                    array_walk($item, $walker);
                }
            };
            array_walk($data, $walker);
            return $data;
        }
    }
