<?php
    
    namespace glx;
    
    use glx\Cache;
    use glx\Common;
    use glx\Events;
    use glx\HTTP;
    use glx\Log;
    use SplStack;

    require_once 'I/Context.php';
    require_once 'Context/CallStack.php';
    require_once 'Context/Implementation.php';
    require_once 'Cache/Persistent.php';
    require_once 'Common/Collection.php';
    
    /**
     * @method static Context\Profile profile($profile = null)
     * @method static Cache\I\Persistent persistent()
     * @method static temporary(string $name = null)
     * @method static Context\I\Callstack callstack()
     * @method static \glx\I\Locale locale(\glx\I\Locale $locale = null)
     * @method static Common\I\Collection input(string $name = null)
     * @method static Events\I\Event|Events\Manager event(string $name = null)
     * @method static ?Common\I\Collection config()
     * @method static HTTP\I\Server http()
     * @method static Log\I\Channel log(string $channel = null)
     */
    class Context
    {
        private static SplStack $context;
        
        public const DEFAULT_PROFILE = 'default';
        
        public static function get(): I\Context
        {
            self::init();
            if (self::$context->isEmpty()) {
                self::new();
            }
            
            return self::$context->top();
        }
        
        public static function new(array $options = []): I\Context
        {
            self::init();
            self::$context->push(new Context\Implementation($options));
            
            return self::$context->top();
        }
        
        public static function release(): void
        {
            if (!isset(self::$context) || self::$context->isEmpty()) {
                return;
            }
            self::$context->pop();
        }
        
        private static function init(): void
        {
            if (!isset(self::$context)) {
                self::$context = new SplStack();
            }
        }
        
        public static function __callStatic($name, $arguments)
        {
            $context = self::get();
            if (method_exists($context, $name)) {
                return call_user_func_array([$context, $name], $arguments);
            }
            
            return null;
        }
    }
 
