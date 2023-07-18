<?php

namespace App\DTA;


class UserDTA
{
    public $id;

    public string $lastname;

    public string $firstname;

    public array $roles;
    public string $email;
    public string $phone_number;
    public string $password;
    public \DateTimeImmutable $created_at;
    public \DateTimeImmutable $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->lastname = $data['lastname'];
        $this->firstname = $data['firstname'];
        $this->roles = json_decode($data['roles']);
        $this->email = $data['email'];
        $this->phone_number = $data['phone_number'];
        $this->password = $data['password'];
        $this->created_at = new \DateTimeImmutable($data['created_at']);
        $this->updated_at = new \DateTimeImmutable($data['updated_at']);
    }
}