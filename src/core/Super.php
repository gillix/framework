<?php
    
    namespace glx\core;
    
    use glx\Context;

    class Super implements I\Super
    {
        use AccessProxy;
        
        public function __construct(
            private array $ancestors,
            private readonly I\Joint $inheritor
        ) {}
        
        public function get(string $name, $type = null): ?I\Joint
        {
            foreach ($this->ancestors as &$ancestor) {
                $ancestor = $this->resolve($ancestor);
                if ($property = $ancestor->obtain($name, $type)) {
                    return new Joint($property, $this->inheritor);
                }
            }
            
            return null;
        }
        
        protected function resolve($ancestor): I\Joint | Node
        {
            if (!is_string($ancestor)) {
                return $ancestor;
            }
            
            $name = $ancestor;
            $type = 'NODE';
            
            if ((strrpos($name, ':')) !== false) {
                return $this->inheritor->get($name, $type);
            }

            $locator = $this->inheritor->owner();
            if ($locator) {
                // Put inheritor to callstack for correct visibility access resolving
                Context::callstack()->enter($this->inheritor);
                $ancestor = $locator->get($name, $type);
                Context::callstack()->release();
                if ($ancestor) {
                    return $ancestor;
                }
            }
            throw new Exception('Can`t find ancestor object: ' . $name);
            
            
            /*
            if($locator = $this->inheritor->owner())
             {
              if(($pos = strpos($name, '/')) === 0)
                return $locator->explore($name, $type);
              elseif($pos !== false)
               {
                [$my, $rest] = explode('/', $name, 2);
                $rest = trim($rest, '/');
                if($found = $locator->property($my, $type) ?? $locator->findUp($my, $type))
                  if(!$rest)
                    return $found;
                  else
                    return $found->explore($rest, $type);
                return NULL;
               }
              if($ancestor = $locator->property($name, $type) ?? $locator->findUp($name, $type))
                return $ancestor;
             }
            throw new Exception('Can`t find ancestor object: '.$name);
            */
        }
        
        public function add($ancestor, string $name = null): void
        {
            if ($ancestor instanceof I\Binder) {
                $this->ancestors[$name ?? $ancestor->name()] = $ancestor->origin();
            } elseif ($ancestor instanceof I\Entity) {
                if ($name) {
                    $this->ancestors[$name] = $ancestor;
                } else {
                    $this->ancestors[] = $ancestor;
                }
            } elseif (is_array($ancestor)) {
                foreach ($ancestor as $key => $item) {
                    $this->add($item, is_string($key) ? $key : null);
                }
            } elseif (is_string($ancestor)) {
                $this->ancestors[$name ?? $ancestor] = $ancestor;
            }
        }
        
        public function fix($inheritor): I\Super
        {
            if ($this->inheritor !== $inheritor && count($this->ancestors)) {
                return new Super($this->ancestors, $inheritor);
            }
            
            return $this;
        }
        
        public function in(string $ancestor): I\Super
        {
            if ($item = $this->ancestors[$ancestor]) {
                return new Super([$item], $this->inheritor);
            }
            
            return $this;
        }
        
        public function is(string $metatype, bool $not = false): bool
        {
            foreach ($this->ancestors as $ancestor) {
                $ancestor = $this->resolve($ancestor);
                if ($ancestor->isMeta($metatype, $not) || $ancestor->super()->is($metatype, $not)) {
                    return !$not;
                }
            }
            
            return $not;
        }
        
        public function isAncestor(I\Joint $ancestor): bool
        {
            foreach ($this->ancestors as $item) {
                if ($item->sameAs($ancestor) || $item->super()->isAncestor($ancestor)) {
                    return true;
                }
            }
            
            return false;
        }
        
        public function select($condition, I\Selection $list = null): I\Selection
        {
            $list ??= new Selection();
            foreach ($this->ancestors as $item) {
                $list = $list->extend($item->select($condition)->map(
                    fn(I\Joint $item) => new Joint($item, $this->inheritor)
                ));
            }
            
            return $list;
        }
    }
