<?php
 
 namespace glx\Config\gcml;
 
 use glx\Config;
 
 require_once __DIR__.'/../I/Parser.php';
 
 class Parser implements Config\I\Parser
 {
     protected static array $grammar;
     protected static string $pattern;
  
     public static function grammar(array $grammar): void
     {
       self::$grammar = $grammar;
       $defines = '';
       foreach(self::$grammar as $name => $pattern)
         $defines .= "(?<$name> $pattern[0])\n";
       self::$pattern = "/(?(DEFINE)\n$defines)[PATTERN]/mx";
     }
 
     public static function parse(string $content, array $callbacks = NULL): array
     {
       return self::fetch('items', $content, $callbacks);
     }
     
     public static function fetch($subject, $data, ?array $callbacks = NULL)
     {
       if(is_array($data) && $data["_$subject"])
         $content = $data["_$subject"];
       else
         $content = $data;
       $grammar = self::$grammar;
       [$pattern, $handler] = $grammar[$subject];
       
       $pattern = preg_replace_callback('/\(\?&(\w+)\)/', static function($matches) use($grammar){
          if($grammar[$matches[1]][1])
            return "(?<_{$matches[1]}> (?&{$matches[1]}))";
          return null;
       }, $pattern);
       
       $pattern = str_replace('[PATTERN]', $pattern, self::$pattern);
       $matches = $result = [];
       if(preg_match_all($pattern, $content, $matches, PREG_SET_ORDER))
         foreach($matches as $match)
           if($handler)
             $result[] = $handler($match, $callbacks);
       if(count($result) === 1)
         return $result[0];
       return $result;
     }
 }
 
 Parser::grammar([
      'name' => ['[@#a-zA-Z0-9\._\-]+', true],
      'directive' => ['%[a-zA-Z0-9\.]+', true],
      'pair' => [
          '((?&directive)|(?&name))[ \t]*:\s*(?&list)',
          function($matches, $callbacks){
              if($matches['_directive'] && $callbacks && ($callback = $callbacks[substr($matches['_directive'], 1)]))
                return $callback(Parser::fetch('list', $matches, $callbacks));
              elseif($matches['_name'])
                return [(string)$matches['_name'] => Parser::fetch('list', $matches, $callbacks)];
              return NULL;
          }
      ],
      'boolean' => [
          'null|true|false',
          function($matches){ return ($val = end($matches)) === 'null' ? NULL : $val === 'true'; }
      ],
      'number' => [
          '^\s*-? (?=[1-9]|0(?!\d)) \d+ (\.\d+)? ([eE] [+-]? \d+)? \s*$',
          function($matches){ return (string)(float)$matches[0] === $matches[0] ? (float)$matches[0] : (int)$matches[0]; }
      ],
      'quoted' => [
          '(?<!\\\)"([\s\S]*?)(?<!\\\)"',
          function($matches){ return trim(end($matches), '"'); }
      ],
      'text' => [
          '(?<!\{)\{(?!\{)([\s\S]*?)(?<!\})\}(?!\})',
          function($matches){ return trim(end($matches), '{}'); }
      ],
      'code' => [
          '\{{2}([\s\S]*?)\}{2}',
          function($matches){ return trim(end($matches), '{}'); }
      ],
      'string' => [
          '(?<=[:,\[]|^)([^\n\],\#:]+)(?=[\],]|$|\#)',
          function($matches){ return trim(end($matches)); }
      ],
      'array' => [
          '\s*\[\s*(?&items)?\s*\]',
          function($matches, $callbacks){
             if($matches['_items'])
               return Parser::fetch('items', $matches, $callbacks) ?? [];
             return [];
          }
      ],
      'list' => [
          '[ \t]*(?&value)([ \t]*,(?&list))?',
          function($matches, $callbacks){
//             if($matches['_value']) $matches['_value'] = trim($matches['_value']);
             $result = Parser::fetch('value', $matches, $callbacks);
             if($matches['_list'])
               $result = array_merge([$result], (array)Parser::fetch('list', $matches, $callbacks));
             return $result;
          }
      ],
      'value' => ['(?&array)|(?&code)|(?&text)|(?&quoted)|(?&boolean)|(?&number)|(?&string)',
          function($matches, $callbacks){
             foreach(['code', 'array', 'text', 'quoted', 'boolean', 'number', 'string'] as $value)
               if($matches["_$value"])
                 return Parser::fetch($value, $matches, $callbacks);
             return NULL;
          }
      ],
      'comment' => ['\#.*$',
          function(){ return NULL; }
      ],
      'item' => ['[ \t]*((?&pair)|(?&value))[ \t]*(?&comment)?',
          function($matches, $callbacks){
             if($matches['_pair'])
               return Parser::fetch('pair', $matches, $callbacks);
             $value = Parser::fetch('value', $matches, $callbacks);
             return $value ? [$value] : NULL;
          }
      ],
      'items' => ['((?&comment)|(?&item))(\s*($|,)\s*(?&items))?',
          function($matches, $callbacks){
             if($matches['_item'])
               $item = Parser::fetch('item', $matches, $callbacks);
             $item ??= [];
             if($matches['_items'])
              {
               $items = (array)(Parser::fetch('items', $matches, $callbacks) ?: []);
               if(is_array($item) && count($item) && !array_key_exists(0, $item))
                 foreach($item as $name => $value)
                   $items[$name] = $value;
               else
                 $items = array_merge($item, $items);
              }
             else
               $items = $item;
             return $items;
          }
      ]
     ]);
  
