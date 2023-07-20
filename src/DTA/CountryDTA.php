<?php

namespace App\DTA;

use DateTimeImmutable;

class CountryDTA
{
    public int $id;
    public string $name;
    public string $code;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->code = $data['code'];
        $this->created_at = $data['created_at'];
        $this->updated_at = $data['updated_at'];
    }
}