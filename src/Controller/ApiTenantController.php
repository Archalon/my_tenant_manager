<?php

namespace App\Controller;

use App\Entity\Tenant;
use App\Service\TenantService;
use App\Repository\TenantRepository;
use App\Dto\TenantInputDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/tenants', name: 'api_tenant_')]
class ApiTenantController extends AbstractController
{
    public function __construct(
        private TenantRepository $tenantRepository,
        private TenantService $tenantService,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $tenants = $this->tenantRepository->findAll();
        return $this->json($tenants, 200, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Tenant $tenant): JsonResponse
    {
        return $this->json($tenant, 200, [], ['groups' => 'tenant:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] TenantInputDto $inputDto,
        ValidatorInterface $validator
    ): JsonResponse {
        $errors = $validator->validate($inputDto);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $tenant = $this->tenantService->createTenantFromDto($inputDto);

        return $this->json($tenant, Response::HTTP_CREATED, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Tenant $tenant): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tenant = $this->tenantService->updateTenant($tenant, $data);

        return $this->json($tenant, 200, [], ['groups' => 'tenant:read']);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(Tenant $tenant): JsonResponse
    {
        $this->entityManager->remove($tenant);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}