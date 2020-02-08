<?php
 namespace glx\core;

 use glx\Context;
 
 class Binder implements I\Binder
 {
    protected string $_name;
    protected string $_profile;
    protected I\Entity $_origin;
    protected int $_visibility = I\Visibility::PUBLIC;
    
    public function __construct(string $name, I\Entity $origin, int $visibility = I\Visibility::PUBLIC, string $profile = NULL)
    {
      $this->_name = $name;
      $this->_origin = $origin;
      $this->_visibility = $visibility;
      if($profile)
        $this->_profile = $profile;
    }
 
    public function name(): string { return $this->_name; }
    public function visibility(): int { return $this->_visibility; }
    public function origin(): I\Entity { return $this->_origin; }
    public function profile(): string { return $this->_profile ?? Context\Profile::DEFAULT; }
 }