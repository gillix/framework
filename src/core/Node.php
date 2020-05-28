<?php

 namespace glx\core;
 
 use glx\Context\Profile;
 use glx\core\I\Visibility;
 use glx\Context;
 use glx\Storage;

 class Node extends Unit implements I\Node
 {
    protected static string $_type = 'NODE';
    protected array $metatype;
    protected I\Selection $children;
    protected array $index;
    protected I\Super $super;
    protected ?array $capture = NULL;

    protected static array $_reserved = [
      'inherit',
      'type',
      'metatype',
      'extends',
      'capture',
      'ancestors'
    ];
    
    use AccessProxy;
  
    public function __construct(array $options = [], $inherit = null)
    {
      parent::__construct($options);
      $this->children = new Selection();
      $this->index = [];

      $this->super = new Super(new \ArrayObject(), $this->this());
      if($inherit = $inherit ?? $options['inherit'] ?? $options['ancestors'] ?? $options['extends'])
        $this->super->add($inherit);
      $this->metatype = (array)($options['type'] ?? $options['metatype']);
      if($options['capture'])
        $this->capture = (array)$options['capture'];
      foreach(self::$_reserved as $word)
        unset($options[$word]);
      if(count($options))
        $this->add($options);
    }

    public function is(string $type, bool $not = false): bool
    {
      return $this->isMeta($type, $not) || parent::is($type, $not);
    }
 
    public function metatype(): array
    {
      return $this->metatype ?? [];
    }
 
    public function isMeta($type, bool $not = false)
    {
      foreach($this->metatype() as $metatype)
        if(strtoupper($type) === strtoupper($metatype))
          return true && !$not;
      return $this->super()->is($type, $not);
    }
  
    public function add($name, $item = NULL, int $visibility = Visibility::PUBLIC)
    {
      if(is_array($name) && $item === NULL)
       {
        foreach($name as $key => $item)
         {
          [$key, $visibility] = explode(':', $key, 2);
          $this->add($key, $item, VisibilityLevels::convert($visibility) ?? Visibility::PUBLIC);
         }
        return $this->this();
       }
      if($name instanceof I\Binder)
       {
        $binder = $name;
        $this->children->add($binder);
        $this->index[$binder->profile()][$binder->name()][] = $binder;
        return $this->this();
       }
      if(!($item instanceof I\Entity || $item instanceof I\Binder))
        $item = Unit::resolve($item);
      if($item && is_string($name))
       {
        [$name, $profile] = explode('@', $name, 2);
        $profile ??= Context::DEFAULT_PROFILE;
        $binder = new Binder($name, $item instanceof I\Binder ? $item->origin() : $item, $visibility, $profile);
        $this->children->add($binder);
        $this->index[$profile][$name][] = $binder;
       }
      return $this->this();
    }

    public function remove($name, $type = NULL, $profile = Profile::DEFAULT)
    {
      if($name instanceof I\Binder)
       {
        $binder = $name;
        $this->children->remove($binder);
        $index = &$this->index;
        array_walk($index[$binder->profile()][$binder->name()], function(I\Binder $item, $i) use($binder, &$index) {
           if($item->origin()->id()->object() === $binder->origin()->id()->object())
             unset($index[$binder->profile()][$binder->name()][$i]);
        });
        if(!count($index[$binder->profile()][$binder->name()])) unset($index[$binder->profile()][$binder->name()]);
       }
      else
       {
        if($variants = $this->index[$profile][$name])
        {
         if(count($variants) === 1 && !$type)
           $property = $variants[0];
         else
           foreach($variants as $i => $item)
             if($type && $item->origin()->is($type))
              { $property = $item; break; }
         if(!$property)
           throw new E\PropertyAccessAmbiguous('Removing property failed: More than one properties with same name. Please specify the type');
         $this->children->remove($property);
         if(($i = array_search($property, $variants, true)) !== false)
           unset($this->index[$profile][$name][$i]);
        }
       }
      return $this->this();
    }
 
    public function has(string $name, $type = NULL): bool
    {
 throw new Exception('Not implemented yet');
      // TODO: нужно иметь возможноть узнать если унаследованное свойство
      // TODO: проверка области видимости
      // возможно стоит убрать из интерфейса
      if(array_key_exists($name, $this->index) && $variants = $this->index[$name])
        if(count($variants) === 1 && !$type)
          return true;
        else
          foreach($variants as $i => $item)
            if(($type && $item->origin()->is($type)) || (!$type && $item->origin()->is('METHOD', true)))
              return true;
      return false;
    }
 
    public function each($callback, $arguments = NULL)
    {
      $this->children->each($callback, $arguments);
      return $this->this();
    }
 
    public function parentOf(I\Joint $entity): bool
    {
      return $entity->childOf($this->this());
    }
 
    /**
     * @param string $name
     * @param string|null $type
     * @return I\Joint|null
     * @throws E\PropertyAccessAmbiguous
     */
    public function property(string $name, $type = NULL): ? I\Joint
    {
  /** @var I\Binder $property */
      $property = NULL;
      $profiles = Context::profile();
      $default = Profile::DEFAULT;
      foreach($profiles as $profile)
        if(array_key_exists($profile, $this->index) && ($property = $this->find($this->index[$profile], $name, $type)))
          break;
      if(!$property)
        $property = $this->find($this->index[$default], $name, $type);
      if($property)
       {
        if($property->visibility() !== Visibility::PUBLIC)
          if($this->contextAccessLevel() < $property->visibility())
            throw new E\PropertyAccessViolation(new Joint($property, $this->this()));
        return new Joint($property, $this->this());
       }
      return NULL;
    }
 
    /**
     * @param array|null $index
     * @param string $name
     * @param string|null $type
     * @return I\Binder|null
     * @throws E\PropertyAccessAmbiguous
     */
    private function find(?array $index, string $name, $type = NULL): ? I\Binder
    {
      if(!is_array($index) || !count($index)) return NULL;
      $property = NULL;
      if(array_key_exists($name, $index) && ($variants = $index[$name]))
       {
        if(count($variants) === 1 && !$type)
          $property = $variants[0];
        else
          foreach($variants as $i => $item)
            if(($type && $item->origin()->is($type)) || (!$type && $item->origin()->is('METHOD', true)))
             { $property = $item; break; }
        if(!$property && !$type)
          throw new E\PropertyAccessAmbiguous('Searching property failed: More than one properties with same name. Please specify the type');
       }
      return $property;
    }
  
    public function get(string $name, $type = NULL): ? I\Joint
    {
      if(($pos = strrpos($name, ':')) !== false)
        if($storage = Storage\Manager::get($storageName = substr($name, 0, $pos)))
          return $storage->root()->get(substr($name, $pos + 1), $type);
        else
          throw new Exception('Can`t fetch external storage: '.$storageName);
     
      if(($pos = strpos($name, '/')) === 0)
        return $this->explore($name, $type);
      elseif($pos !== false)
       {
        [$my, $rest] = explode('/', $name, 2);
        $rest = trim($rest, '/');
        if($found = $this->get($my, $type))
          if(!$rest)
            return $found;
          else
            return $found->explore($rest, $type);
        return NULL;
       }
      
      return $this->obtain($name, $type) ?? $this->findUp($name, $type);
    }
  
    public function findUp(string $name, $type = NULL): ? I\Joint
    {
      if($parent = $this->this()->parent())
       {
        try { if($property = $parent->obtain($name, $type)) return $property; }
        catch(E\PropertyAccessViolation $e) { /* log notice? */ }
        return $parent->findUp($name, $type);
       }
      return NULL;
    }
  
    public function explore(string $path, $type = NULL, bool $strict = false): ? I\Joint
    {
      $root = $this->this()->root();
      if($path === '/' || $path === '') return $root;
      list($my, $rest) = explode('/', $path, 2);
      $rest = trim($rest, '/');
      if($my === '' && $rest !== NULL)
        return $root->explore($rest);
      if($my)
       {
        if($my === '.')
          if($rest) return $this->explore($rest, $type, $strict);
          else return (!$type || $this->is($type)) ? $this->this() : NULL;
        if($my === '..')
         {
          if($parent = $this->this()->parent())
            if($rest) return $parent->explore($rest, $type, $strict);
            else return (!$type || $parent->is($type)) ? $parent : NULL;
          throw new Exception('Can`t fetch parent node: parent is not exist.');
         }
        if($child = $strict ? $this->property($my, $type) : $this->obtain($my, $type))
          if(!$rest)
            return $child;
          else
            return $child->explore($rest);
        if($this->capture($my))
          if($rest)
            return $this->explore($rest, $type, $strict);
          else
            return $this;
       }
      return NULL;
    }
  
    public function obtain(string $name, $type = NULL): ? I\Joint
    {
      try { if($property = $this->property($name, $type)) return $property; }
      catch(E\PropertyAccessViolation $e) {}
      return $this->super()->get($name, $type);
    }
 
    public function extend(?array $options = NULL): ? string
    {
     // TODO: пождумать: может быть сохранять в джоинте
      if($this->capture === NULL) return false;
      $id = $this->id().':captured';
      $cache = Context::temporary();
      if(isset($cache[$id]))
       {
        $extra = [];
        foreach($cache[$id] as $name => $value)
          $extra[] = $options && $options[$name] ? $options[$name] : $value;
       }
      elseif($options && is_array($this->capture))
       {
        $extra = [];
        foreach($this->capture as $name)
          if($options[$name])
            $extra[] = $options[$name];
          else
            break;
       }
      if(isset($extra) && count($extra))
        return implode('/', $extra).'/';
      return NULL;
    }
 
    protected function capture(string $value): bool
    {
      if($this->capture === NULL || !count($this->capture)) return false;
      $context = Context::get();
      $cache = $context->temporary();
      
      $id = $this->id().':capture';
      if(!isset($cache[$id]))
        $cache[$id] = $this->capture;
      $capture = $cache[$id]->array();
      $key = array_shift($capture);
      $cache[$id] = $capture;
      
      $id = $this->id().':captured';
      if(!isset($cache[$id])) $cache[$id] = [];
      $cache[$id][$key] = $value;
      $context->input()[$key] = $value;
      return true;
    }
 
    protected function contextAccessLevel(): int
    {
      $callstack = Context::get()->callstack();
      if($callstack->empty()) return Visibility::PUBLIC;
      
      $foreign = $callstack->current()->owner();
      if($foreign && $foreign->sameAs($this->this()))
        return Visibility::PRIVATE;
      elseif($foreign && ($foreign->childOf($this->this()) || $foreign->inheritedFrom($this->this())))
        return Visibility::PROTECTED;
      else
        return Visibility::PUBLIC;
    }
  
    public function select($condition = NULL): I\Selection
    {
      $new = [];
      $me = $this->this();
      $level = $this->contextAccessLevel();
      $currentProfile = Context::profile();
      $this->children->each(function() use(&$new, $me, $level, $currentProfile) {
         // select only current profile or default
         $profile = $this->profile();
         if($profile !== $currentProfile && $profile !== Context::DEFAULT_PROFILE)
           return;
         // check property visibility
         if($this->visibility() !== Visibility::PUBLIC)
           if($level < $this->visibility())
             return;
         $new[] = new Joint($this, $me);
      });
      return $this->super()->select($condition, (new Selection($new))->filter($condition));
    }
  
    public function super(string $ancestor = NULL): I\Super
    {
      $super = $this->super->fix($this->this());
      return $ancestor ? $super->in($ancestor) : $super;
    }
  
    public function inheritedFrom($ancestor): bool
    {
      return $this->super()->isAncestor($ancestor);
    }
  
    public function ancestorOf($inheritor): bool
    {
      return $inheritor->inheritedFrom($this->this());
    }
  
    public function call($method, array $arguments = NULL)
    {
      $toCall = NULL;
      if($method instanceof I\Joint && $method->origin() instanceof I\Invokable)
        $toCall = $method;
      elseif($method instanceof I\Invokable)
        $toCall = new Joint(new Binder('', $method), $this->this());
      elseif($method instanceof \Closure)
        $toCall = new Joint(new Binder('', Method::new($method)), $this->this());
      elseif(is_string($method))
        $toCall = $this->get($method, 'method');
      if($toCall)
        return $toCall->apply($this->this(), $arguments);
      return NULL;
    }
 
    public function count(): int
    {
      return $this->children->count();
    }
  
    public static function new(...$arguments): I\Node
    {
      return new static(...$arguments);
    }
  
    public static function resolve($value): ? I\Node
    {
      if(is_array($value))
        return self::new($value);
      return NULL;
    }

    public static function reserved(): array
    {
      $classes = class_parents(static::class);
      $classes[] = static::class;
      return array_merge(...array_filter($classes, fn($item) => isset($item::$_reserved)));
    }
 }
 
 // register class as value resolver
 Unit::resolver(Node::class);


 /**
  * global function for simplify usage
  * creates new object of Node
  * @param mixed ...$arguments
  * @return I\Node
  */
 function node(...$arguments): I\Node
 {
   return Node::new(...$arguments);
 }
 
 
