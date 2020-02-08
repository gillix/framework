<?php
 namespace glx\Storage\FS\I;

 interface Factory 
 {
    public static function probe(array $info, Structure $current): bool;
    public static function create(array $info, Structure $current, Storage $storage): array;
    public static function clear(array $record, Storage $storage): void;
    public static function check(array $record, Storage $storage): void;
    public static function recreate(array $record, Storage $storage): array;
 }