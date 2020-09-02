<?php
    
    namespace glx\core;
    
    use glx\Library;

    class Component extends Unit
    {
        protected static string     $_type = 'COMPONENT';
        protected Library\I\Factory $factory;
        protected                   $component;
        protected array             $bind;
        protected array             $use;
        
        public function __construct(array $options = [])
        {
            if ($options['use']) {
                $this->use = (array)$options['use'];
            }
            if (is_array($options['bind'])) {
                $this->bind = $options['bind'];
            }
            if ($options['component']) {
                $this->component = $options['component'];
            }
            parent::__construct($options);
        }
        
        protected function component()
        {
            if (is_string($this->component)) {
                $this->component = $this->factory()->get($this->component);
            }
            
            return $this->component;
        }
        
        protected function factory(): Library\I\Factory
        {
            if (!isset($this->factory)) {
                $options = [];
                if (isset($this->use) && ($parent = $this->parent())) {
                    $factories = [];
                    foreach ($this->use as $component) {
                        $locator = $this->this()->name() === $component ? $parent->parent() : $parent;
                        if (!$locator) {
                            continue;
                        }
                        if (($component = $locator->get($component, 'COMPONENT')) && $component->origin() instanceof static) {
                            $factories[] = $component->factory();
                        }
                    }
                    if (count($factories)) {
                        $options['use'] = $factories;
                    }
                }
                if (isset($this->bind)) {
                    $options['bind'] = $this->bind;
                }
                $this->factory = new Library\Factory($options);
            }
            
            return $this->factory;
        }
        
        public function __call($name, $arguments)
        {
            $component = $this->component();
            if ($component && method_exists($component, $name)) {
                return call_user_func_array([$component, $name], $arguments);
            }
            
            return null;
        }
        
        public function __toString()
        {
            return "[{$this->name()} library component]";
        }
    }