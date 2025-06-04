<?php

namespace App\Controller;

use App\Service\AuditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/audits')]
class ApiAuditController extends AbstractController
{
    private $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    #[Route('', name: 'api_audits_index', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $audits = $this->auditService->getAllAudits();

        return $this->json($audits, Response::HTTP_OK, [], ['groups' => 'audit:read']);
    }

    #[Route('/{id}', name: 'api_audits_show', methods: ['GET'])]
    public function show(int $id, SerializerInterface $serializer): JsonResponse
    {
        $audit = $this->auditService->getAuditById($id);

        if (!$audit) {
            return $this->json(['error' => 'Audit not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($audit, Response::HTTP_OK, [], ['groups' => 'audit:read']);
    }
}