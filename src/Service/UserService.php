<?php

namespace App\Service;

use App\Dto\UserCreateDto;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAllUsers();
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function createUserFromDto(UserCreateDto $dto): User
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new \Exception('Email já registado.');
        }

        $user = new User();
        $user->setUsername($dto->username);
        $user->setEmail($dto->email);
        $user->setRoles(['ROLE_API']);
        $user->setIsActive(true);
        $user->setPassword($dto->password);
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->save($user);

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->save($user);

        return $user;
    }
}