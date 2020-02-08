<?php
 
 namespace glx\Config\yaml;
 
 use glx\Config;
 
 require_once __DIR__.'../I/Parser.php';
 
 class Parser implements Config\I\Parser
 {
    public static function parse(string $content): array
    {
      if(!extension_loaded('yaml'))
        throw new \glx\Exception('Need to load module "yaml" for parsing yaml content');
      return yaml_parse($content);
    }
 }