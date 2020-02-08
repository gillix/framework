<?php
 namespace glx\Session\ID\I;
 
 
 interface Provider
 {
    public function id(): string;
    public function exist(): bool;
    public function create(): string;
    public function delete(): void;
 }