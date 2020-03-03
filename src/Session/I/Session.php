<?php
 namespace glx\Session\I;

 use glx\Common;

 interface Session extends Common\I\Collection
 {
    public function has(string $name): bool;
    public function get(string $name, $default = NULL);
    public function set(string $name, $value): void;
    public function purge(): void;
    public function forget(string $name): void;
    public function refresh(): void;
    public function destroy(): void;
    public function started(): bool;
    public function create(int $lifetime = 0): bool;
 }