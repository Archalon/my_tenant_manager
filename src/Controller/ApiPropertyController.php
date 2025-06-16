<?php

namespace App\Controller;

use App\Dto\PropertyCreateDto;
use App\Service\PropertyService;
use App\Event\AuditLogEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[Route('/api/properties')]
class ApiPropertyController extends AbstractController
{
    public function __construct(
        private PropertyService $propertyService,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    #[Route('', name: 'api_properties_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->propertyService->getAllProperties(), Response::HTTP_OK, [], ['groups' => 'property:read']);
    }

    #[Route('/{name}', name: 'api_properties_show', methods: ['GET'])]
    public function show(string $name): JsonResponse
    {
        $property = $this->propertyService->getByName($name);
        if (!$property) {
            return $this->json(['error' => 'Property not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($property, Response::HTTP_OK, [], ['groups' => 'property:read']);
    }

    #[Route('', name: 'api_properties_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] PropertyCreateDto $createDto,
        ValidatorInterface $validator,
        UserInterface $user
    ): JsonResponse
    {
        $errors = $validator->validate($createDto);

        if (count($errors) > 0) {
            return $this->json((string)$errors, Response::HTTP_BAD_REQUEST);
        }

        $property = $this->propertyService->createPropertyFromDto($createDto, $user);

        return $this->json($property, Response::HTTP_CREATED, [], ['groups' => 'property:read']);
    }

    #[Route('/{name}', name: 'api_properties_update', methods: ['PUT'])]
    public function update(Request $request, string $name): JsonResponse
    {
        $property = $this->propertyService->getByName($name);
        if (!$property) {
            return $this->json(['error' => 'Property not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $property = $this->propertyService->updateProperty($property, $data);

        return $this->json($property, Response::HTTP_OK, [], ['groups' => 'property:read']);
    }

    #[Route('/{name}', name: 'api_properties_delete', methods: ['DELETE'])]
    public function delete(string $name): JsonResponse
    {
        $property = $this->propertyService->getByName($name);
        if (!$property) {
            return $this->json(['error' => 'Property not found'], Response::HTTP_NOT_FOUND);
        }

        $this->propertyService->deleteProperty($property);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}