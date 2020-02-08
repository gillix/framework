<?php
 namespace glx\Log\I;
 
 interface Channel
 {
   public function name(): string;
   public function debug(...$arguments): self;
   public function info(...$arguments): self;
   public function notice(...$arguments): self;
   public function warning(...$arguments): self;
   public function error(...$arguments): self;
   public function critical(...$arguments): self;
   public function alert(...$arguments): self;
   public function emergency(...$arguments): self;
   public function log(...$arguments): self;
 }
