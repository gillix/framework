<?php
 
 namespace glx\HTTP\Server\I;
 
 use glx\HTTP;
 
 interface Response extends HTTP\I\Response
 {
    public function header($name, string $value = NULL): string;
    public function headers(array $headers = NULL): array;
    public function body(string $content = NULL): string;
    public function contentType(string $type = NULL): string;
    public function status(int $code = NULL): int;
    public function redirect($url): void;
    public function apply(): void;
 }