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

    public function edit(User $user){
        $statement = $this->pdo->prepare('UPDATE `user` SET `address_id` = null, `lastname` = ?, `firstname` = ?, `email` = ?, `phone_number` = ?, `password` = ?, `roles`= ? WHERE `id` = ?');
        $statement->execute([$user->getLastname(), $user->getFirstname(), $user->getEmail(), $user->getPhoneNumber(), $user->getPassword(), json_encode($user->getRoles()),$user->getId()]);
       
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

    public function getAllByRole(string $role): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM `user` WHERE JSON_CONTAINS(roles, ?)');
        $statement->execute(['"' . $role . '"']);
        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if ($data === false) {
            return [];
        }

        $userDtaConverter = new UserDtaConverter();

        $users = [];
        foreach ($data as $user) {
            $userDta = new UserDTA($user);
            $users[] = $userDtaConverter->toUser($userDta);
        }

        return $users;
    }

    public function saveCustomer(array $datas, string $type):?int{
        if(empty($datas['id'])){
            $customer = new Customer();
        } else{
            $customer = $this->find($datas['id']);
        }

        if(!empty($datas['password'])){
            $password = password_hash($datas['password'], PASSWORD_DEFAULT);
            $customer->setPassword($password);
        }else{
            $customer->setPassword($customer->getPassword());
        }

        $customer->setLastname($datas['lastname']);
        $customer->setFirstname($datas['firstname']);
        $customer->setEmail($datas['email']);
        $customer->setPhoneNumber($datas['phone_number']);

        if($type === 'create'){
            return $this->add($customer);
        } elseif($type === 'edit'){
            return $this->edit($customer);
        }
        
    }

    public function saveAdmin(array $datas, string $type):?int{
        if(empty($datas['id'])){
            $admin = new Admin();
        } else{
            $admin = $this->find($datas['id']);
        }

        if(!empty($datas['password'])){
            $password = password_hash($datas['password'], PASSWORD_DEFAULT);
            $admin->setPassword($password);
        }else{
            $admin->setPassword($admin->getPassword());
        }

        $admin->setLastname($datas['lastname']);
        $admin->setFirstname($datas['firstname']);
        $admin->setEmail($datas['email']);
        $admin->setPhoneNumber($datas['phone_number']);

        if($type === 'create'){
            return $this->add($admin);
        } elseif($type === 'edit'){
            return $this->edit($admin);
        }
        
    }

    public function deleteOneById(int $id){
        $statement = $this->pdo->prepare('DELETE FROM `user` WHERE id = ?');
        $statement->execute([$id]);
    }
}
