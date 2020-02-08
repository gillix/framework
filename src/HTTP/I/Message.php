<?php
 
 namespace glx\HTTP\I;
 
 interface Message
 {
    public function header($name): string;
    public function headers(): array;
    public function version(): string;
    public function body(): string;
 }