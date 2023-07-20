<?php

namespace App\DTA;

use DateTimeImmutable;

class CouponDTA
{
    public int $id;
    public string $name;
    public string $code;
    public int $percent;
    public string $duration;
    public int $duration_in_months;
    public bool $valid;
    public DateTimeImmutable $created_at;
    public DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->code = $data['code'];
        $this->percent = $data['percent'];
        $this->duration = $data['duration'];
        $this->duration_in_months = $data['duration_in_months'];
        $this->valid = $data['valid'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}