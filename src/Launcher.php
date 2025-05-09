<?php
    
    namespace glx;
    
    use glx\Common;
    use glx\Config;
    use glx\Library;
    use glx\Storage;

    abstract class Launcher implements I\Launcher, Events\I\Emitter
    {
        protected Common\I\Collection $config;
        protected Storage\I\Storage   $storage;
        protected I\Context $context;

        use Events\Delegated;
        
        public function __construct($config)
        {
            if (is_string($config)) {
                $config = Config\Reader::read($config);
            }
            if (is_array($config)) {
                $this->config = new Common\Collection($config);
            } elseif ($config instanceof Common\I\Collection) {
                $this->config = $config;
            }
            if ($storage = $config['storage']) {
                if (is_array($storage)) {
                    if ($storage['main']) {
                        foreach ($storage as $name => $item) {
                            if ($name === 'main') {
                                $this->storage = Storage\Manager::get($item, $item);
                            } else {
                                Storage\Manager::register($name, Storage\Manager::get($item, $item));
                            }
                        }
                    } else {
                        $this->storage = Storage\Manager::get($storage, $storage);
                    }
                } elseif (is_string($storage)) {
                    $this->storage = Storage\Manager::get($storage);
                }
            }
            if (!isset($this->storage)) {
                throw new Exception('Main storage not initialised');
            }
            if ($config['components']) {
                Library\Factory::defaults($config['components']);
            }
        }
        
        abstract protected function getContextOptions(): array;
        
        protected function initContext(): I\Context
        {
            return $this->context = Context::new($this->getContextOptions());
        }
        
        protected function closeContext(): void
        {
            Context::release();
        }
        
        abstract protected function execute(I\Context $context, string|null $path = null);
        
        public function run($path = null): ?string
        {
            $context = null;
            try {
                $context = $this->initContext();
                try {
                    $this->fire('launcher.start', [$path]);
                    $result = $this->execute($context, $path);
                    $this->fire('launcher.finish', [$result]);
                    
                    return $result;
                } catch (Stop $e) {
                    return $e->out();
                }
            } catch (Error $e) {
                $context->log()->error($e->getMessage(), [
                    'data'   => $e->context(),
                    'gillix' => $e->stack(),
                    'php'    => [
                        'file'  => $e->getFile(),
                        'line'  => $e->getLine(),
                        'trace' => $e->getTrace()
                 ]
                ]);
            } catch (Exception $e) {
                $context->log()->critical($e->getMessage(), [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTrace()
                ]);
            } catch (\Exception|\Error $e) {
                if ($context) {
                    $context->log()->critical($e->getMessage(), [
                        'file'  => $e->getFile(),
                        'line'  => $e->getLine(),
                        'trace' => $e->getTrace()
                    ]);
                } else {
                    throw $e;
                }
            }
            finally {
                $this->closeContext();
            }
            
            return null;
        }
        
        
    }
