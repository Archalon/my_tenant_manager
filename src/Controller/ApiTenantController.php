<?php

namespace App\Controller;

use App\Dto\TenantCreateDto;
use App\Service\TenantService;
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

#[Route('/api/tenants')]
class ApiTenantController extends AbstractController
{
    public function __construct(
        private TenantService $tenantService,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    #[Route('', name: 'api_tenants_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->tenantService->getAllTenants(), Response::HTTP_OK, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{code}', name: 'api_tenants_show', methods: ['GET'])]
    public function show(string $code): JsonResponse
    {
        $tenant = $this->tenantService->getByCode($code);
        if (!$tenant) {
            return $this->json(['error' => 'Tenant not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($tenant, Response::HTTP_OK, [], ['groups' => 'tenant:read']);
    }

    #[Route('', name: 'api_tenants_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TenantCreateDto $createDto,
        ValidatorInterface $validator,
        UserInterface $user
    ): JsonResponse {
        $errors = $validator->validate($createDto);

        if (count($errors) > 0) {
            return $this->json((string) $errors, Response::HTTP_BAD_REQUEST);
        }

        $tenant = $this->tenantService->createTenantFromDto($createDto, $user);

        return $this->json($tenant, Response::HTTP_CREATED, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{code}', name: 'api_tenants_update', methods: ['PUT'])]
    public function update(Request $request, string $code): JsonResponse
    {
        $tenant = $this->tenantService->getByCode($code);
        if (!$tenant) {
            return $this->json(['error' => 'Tenant not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $tenant = $this->tenantService->updateTenant($tenant, $data);

        return $this->json($tenant, Response::HTTP_OK, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{code}', name: 'api_tenants_delete', methods: ['DELETE'])]
    public function delete(string $code): JsonResponse
    {
        $tenant = $this->tenantService->getByCode($code);
        if (!$tenant) {
            return $this->json(['error' => 'Tenant not found'], Response::HTTP_NOT_FOUND);
        }

        $this->tenantService->deleteTenant($tenant);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}