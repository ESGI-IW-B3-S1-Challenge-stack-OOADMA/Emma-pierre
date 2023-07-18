<?php

namespace App\Repository;

use App\DTA\Converter\UserDtaConverter;
use App\DTA\UserDTA;
use App\Entity\Admin;
use App\Entity\Customer;
use App\Entity\User;

class UserRepository extends AbstractRepository
{
    public function add(User $user): int
    {
        $statement = $this->pdo->prepare('INSERT INTO `user` (`address_id`, `lastname`, `firstname`, `email`, `phone_number`, `password`, `roles`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([null, $user->getLastname(), $user->getFirstname(), $user->getEmail(), $user->getPhoneNumber(), $user->getPassword(), json_encode($user->getRoles())]);
        return $this->pdo->lastInsertId();
    }

    public function find(int $int): User|null
    {
        $statement = $this->pdo->prepare('SELECT * FROM `user` WHERE id = ?');
        $statement->execute([$int]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $userDta = new UserDTA($data);

        $userDtaConverter = new UserDtaConverter();

        return $userDtaConverter->toUser($userDta);
    }

    public function findOneByEmail(string $email): User|null
    {
        $statement = $this->pdo->prepare('SELECT * FROM `user` WHERE email = ? LIMIT 1');
        $statement->execute([$email]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }

        $userDta = new UserDTA($data);

        $userDtaConverter = new UserDtaConverter();

        return $userDtaConverter->toUser($userDta);
    }
}
