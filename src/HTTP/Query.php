<?php
    
    namespace glx\HTTP;
    
    
    use glx\Common;

    class Query extends Common\Collection implements I\Query
    {
        protected array $params;
        
        public function __construct($params)
        {
            $array = [];
            if (is_string($params)) {
                parse_str($params, $array);
            } elseif (is_array($params)) {
                $array = $params;
            } elseif ($params instanceof I\Query) {
                $array = $params->array();
            }
            parent::__construct($array);
        }
        
        public function __toString()
        {
            return http_build_query($this->array());
        }
    }