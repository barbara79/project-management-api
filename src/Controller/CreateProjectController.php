<?php

namespace App\Controller;

use App\Dto\CreateProjectDTO;
use App\Handler\CreateProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\DataMapper\ProjectMapper;

class CreateProjectController extends AbstractController
{

    public function __construct(
        readonly NormalizerInterface $normalizer,
    )
    {
    }

    #[Route(path:'/projects', name: 'project_create', methods: ['POST'])]
    public function __invoke(Request $request, CreateProjectHandler $handler, SerializerInterface $serializer, ProjectMapper $projectMapper): JsonResponse
    {
        $projectDto = $serializer->deserialize($request->getContent(), CreateProjectDTO::class, 'json');
        $projectMapped = $projectMapper->fromDTO($projectDto);

        $project = $handler->handle($projectMapped);

        $normalized = $this->normalizer->normalize($project, null, ['datetime_format' => 'Y-m-d']);

        return $this->json(['project' => $normalized], 201);
    }
}
