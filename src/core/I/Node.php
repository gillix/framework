<?php
 namespace glx\core\I;

 use glx\Events;
 
 interface Node extends Entity, Joint, Inheritor, Ancestor, Events\I\Emitter, Caller, Rewriter
 {
    public function metatype(): array;
    public function parentOf(Joint $entity): bool;
    public function add($name, $item, int $visibility = Visibility::PUBLIC);
    public function remove(string $name, $type = NULL);
    public function has(string $name, $type = NULL): bool;
    public function property(string $name, $type = NULL): ? Joint;
    public function get(string $name, $type = NULL): ? Joint;
    public function select($condition = NULL): Selection;
 }