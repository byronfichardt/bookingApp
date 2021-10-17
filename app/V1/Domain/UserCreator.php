<?php

namespace App\V1\Domain;

use App\V1\Application\Models\User;
use App\V1\Domain\dtos\UserDto;

class UserCreator
{
    public function create(UserDto $userDto): User
    {
        $user = User::findByEmail($userDto->getEmail());

        if($user) {
            return $user;
        }

        return User::create([
            'name' => $userDto->getName(),
            'email' => $userDto->getEmail(),
            'phone' => $userDto->getPhone()
        ]);
    }
}
