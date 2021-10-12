<?php
    
    namespace glx\Config\yaml;
    
    use glx\Config;
    use glx\Exception;
    use Symfony\Component\Yaml\Exception\ParseException;
    use Symfony\Component\Yaml\Yaml;

    require_once __DIR__ . '/../I/Parser.php';
    
    class Parser implements Config\I\Parser
    {
        public static function parse(string $content): array
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
    }