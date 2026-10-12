<?php

namespace App\Services;

use App\DTOs\CreateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(CreateUserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);
    }
}