<?php
    
    spl_autoload_register(function ($className) {
        $className = ltrim($className, '\\');
        if (stripos($className, 'glx') !== 0) {
            return false;
        }
        $fileName = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\', 4)) {
            $namespace = substr($className, 4, $lastNsPos - 4);
            $className = substr($className, $lastNsPos + 1);
            if ($namespace === '' && stripos($className, '_') === 0) {
                $namespace = 'Core\Basics';
            } elseif (strpos($namespace, 'core') !== false) {
                $namespace = str_replace('core', 'Core', $namespace);
            }
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        } else {
            $className = substr($className, 4);
        }
        $file = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . '.php';
        if (!file_exists($file)) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . $fileName . $className . DIRECTORY_SEPARATOR . $className . '.php';
        }
        if (file_exists($file)) {
            require $file;
            
            return true;
        }
        
        return false;
    });