<?php

namespace App\Entity;

class Country
{
    private int $id;
    private string $name;
    private \DateTimeImmutable $created_at;
    private \DateTimeImmutable $updated_at;
}