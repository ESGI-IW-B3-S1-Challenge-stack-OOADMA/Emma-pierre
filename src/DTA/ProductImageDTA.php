<?php

namespace App\DTA;

class ProductImageDTA
{
    public int $id;
    public string $path;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;
    //private int $position;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->path = $data['path'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
        //$this->position = $data['position'];
    }
}