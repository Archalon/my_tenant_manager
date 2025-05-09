<?php

namespace App\Controller;

use App\Dto\FeatureFlagCreateDto;
use App\Service\FeatureFlagService;
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

#[Route('/api/feature_flags')]
class ApiFeatureFlagController extends AbstractController
{
    public function __construct(
        private FeatureFlagService $featureFlagService,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    #[Route('', name: 'api_feature_flags_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->featureFlagService->getAllFeatureFlags(), Response::HTTP_OK, [], ['groups' => 'feature_flag:read']);
    }

    #[Route('/{name}', name: 'api_feature_flags_show', methods: ['GET'])]
    public function show(string $name): JsonResponse
    {
        $featureFlag = $this->featureFlagService->getByName($name);
        if (!$featureFlag) {
            return $this->json(['error' => 'FeatureFlag not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($featureFlag, Response::HTTP_OK, [], ['groups' => 'feature_flag:read']);
    }

    #[Route('', name: 'api_feature_flags_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] FeatureFlagCreateDto $createDto,
        ValidatorInterface $validator,
        UserInterface $user
    ): JsonResponse {
        $errors = $validator->validate($createDto);

        if (count($errors) > 0) {
            return $this->json((string)$errors, Response::HTTP_BAD_REQUEST);
        }

        $featureFlag = $this->featureFlagService->createFeatureFlagFromDto($createDto, $user);

        return $this->json($featureFlag, Response::HTTP_CREATED, [], ['groups' => 'feature_flag:read']);
    }

    #[Route('/{name}', name: 'api_feature_flags_update', methods: ['PUT'])]
    public function update(Request $request, string $name): JsonResponse
    {
        $featureFlag = $this->featureFlagService->getByName($name);
        if (!$featureFlag) {
            return $this->json(['error' => 'Feature Flag not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $featureFlag = $this->featureFlagService->updateFeatureFlag($featureFlag, $data);

        return $this->json($featureFlag, Response::HTTP_OK, [], ['groups' => 'feature_flag:read']);
    }

    #[Route('/{name}', name: 'api_feature_flags_delete', methods: ['DELETE'])]
    public function delete(string $name): JsonResponse
    {
        $featureFlag = $this->featureFlagService->getByName($name);
        if (!$featureFlag) {
            return $this->json(['error' => 'FeatureFlag not found'], Response::HTTP_NOT_FOUND);
        }

        $this->featureFlagService->deleteFeatureFlag($featureFlag);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}