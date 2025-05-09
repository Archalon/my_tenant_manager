<?php

namespace App\Service;

use App\Dto\UserCreateDto;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\CreateUserEvent;
use App\Event\UpdateUserEvent;
use App\Event\DeleteUserEvent;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public function save(User $user): void
    {
        $this->userRepository->save($user);
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
            throw new Exception('Email já registado.');
        }

        $user = new User();
        $user->setUsername($dto->username);
        $user->setEmail($dto->email);
        $user->setRoles(['ROLE_API']);
        $user->setIsActive(true);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $dto->password
        );
        $user->setPassword($hashedPassword);

        $user->setCreatedAt(new DateTimeImmutable());

        $this->save($user);

        $this->eventDispatcher->dispatch(new CreateUserEvent($user));

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        $user->setUsername($data['username'] ?? $user->getUsername());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setUpdatedAt(new DateTimeImmutable());

        $this->save($user);

        $this->eventDispatcher->dispatch(new UpdateUserEvent($user, $data));

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $user->setDeletedAt(new DateTimeImmutable());
        $this->save($user);

        $this->eventDispatcher->dispatch(new DeleteUserEvent($user));
    }
}