<?php
    
    namespace glx\Config\yaml;
    
    use glx\Config;
    use glx\Exception;
    use Symfony\Component\Yaml\Exception\ParseException;
    use Symfony\Component\Yaml\Yaml;

    require_once __DIR__ . '/../I/Parser.php';
    
    class Parser implements Config\I\Parser
    {
        public static function parse(string $content, array|null $callbacks = null): array
        {
            try {
                $result = [];
                if (extension_loaded('yaml')) {
                    $result = yaml_parse($content);
                } else {
                    $result = Yaml::parse($content);
                }

                return self::fetchDirectives($callbacks, $result);
            } catch (\Exception $e) {
                throw new Exception('Yaml parser error: ' . $e->getMessage());
            }

        }

        protected static function fetchDirectives(array $directives, array $data): array
        {
            $walker = static function (&$array) use ($directives, &$walker) {
                foreach ($array as $key => &$item) {
                    if (isset($directives[$key])) {
                        $callback = $directives[$key];
                        if (is_callable($callback)) {
                            unset($array[$key]);
                            $array += $callback($item);
                        }
                    } elseif (is_array($item)) {
                        $walker($item);
                    }
                }
            };
            $walker($data);
            return $data;
        }
    }
