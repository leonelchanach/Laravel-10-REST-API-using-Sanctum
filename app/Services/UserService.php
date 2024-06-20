<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }
    public function loginUser(array $credentials)
{
    $user = $this->userRepository->findByEmail($credentials['email']);

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return null;
    }

    // Generar token
    $token = $user->createToken('auth_token')->plainTextToken;

    return [
        'user' => $user,
        'token' => $token
    ];
}
}
