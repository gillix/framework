<?php
 namespace glx\core\I;

 interface Joint extends Binder
 {
    public function parent(): ? Joint;
    public function closest($type): ? Joint;
    public function location(): string;
    public function path(array $options = NULL): string;
    public function childOf(Joint $parent): bool;
    public function root(): Joint;
 }