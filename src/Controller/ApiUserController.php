<?php

namespace App\Controller;

use App\Dto\UserCreateDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/users')]
class ApiUserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}

    #[Route('', name: 'api_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->userService->getAllUsers(), Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/{email}', name: 'api_users_show', methods: ['GET'])]
    public function show(string $email): JsonResponse
    {
        $user = $this->userService->getByEmail($email);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('', name: 'api_users_create', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserCreateDto $createDto, ValidatorInterface $validator): JsonResponse {
        $errors = $validator->validate($createDto);

        if (count($errors) > 0) {
            return $this->json((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->createUserFromDto($createDto);

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'user:read']);
    }

    #[Route('/{email}', name: 'api_users_update', methods: ['PUT'])]
    public function update(Request $request, string $email): JsonResponse
    {
        $user = $this->userService->getByEmail($email);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $user = $this->userService->updateUser($user, $data);

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'user:read']);
    }

    #[Route('/{email}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(string $email): JsonResponse
    {
        $user = $this->userService->getByEmail($email);
        if (!$user) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $this->userService->delete($user);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}