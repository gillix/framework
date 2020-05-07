<?php
 
 namespace glx\Common;
 
 require_once 'I/_string.php';
 
 class _string implements I\_string
 {
    protected $__string;
    
    public function __construct($string)
    {
      $this->__string = $string instanceof self ? $string->__string : $string;
    }
 
    public function trim($chars = NULL): I\_string { return new _string(trim($this->__string)); }
    public function length(): int { return strlen($this->__string); }
    public function contains(string $find): bool { return strpos($find, $this->__string) !== false; }
    public function position(string $find, int $offset = NULL): int { return strpos($find, $this->__string, $offset); }
    public function substring(int $start, int $limit = 0): I\_string { return new _string(substr($this->__string, $start, $limit)); }
    public function replace($old, string $new = NULL, int $count = NULL): I\_string
    {
      if(is_array($old) && $new === NULL)
        $replaced = str_replace(array_keys($old), $old, $this->__string, $count);
      else
        $replaced = str_replace($old, $new, $this->__string, $count);
      return new _string($replaced);
    }
    public function split(string $delimiter): array { return explode($delimiter, $this->__string); }
    public function concat(string $other): I\_string { return new _string($this->__string . $other); }
    public function lower(): I\_string { return new _string(strtolower($this->__string)); }
    public function upper(): I\_string { return new _string(strtoupper($this->__string)); }
    public static function format(string $format, ...$arguments): I\_string { return new _string(sprintf($format, ...$arguments)); }
    /**
     * get parsed from markdown to html
     */
    public function md(): _string
    {
      $parser = new \Parsedown();
      return new _string($parser->line((string)$this->__string));
    }
    public function __toString() { return (string)$this->__string; }
 }
 