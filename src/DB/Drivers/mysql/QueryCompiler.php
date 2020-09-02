<?php
    
    namespace glx\DB\Drivers\mysql;
    
    use glx\DB;

    class QueryCompiler extends DB\QueryCompiler
    {
        public function __construct()
        {
            $this->unitsCompilerClass = UnitsCompiler::class;
        }
        
        
    }