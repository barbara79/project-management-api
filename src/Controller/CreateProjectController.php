<?php

namespace App\Controller;

use App\Dto\CreateProjectDTO;
use App\Dto\ProjectDTOInterface;
use App\Handler\CreateProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\DataMapper\ProjectMapper;
use App\Exception\ExceptionInterface;

class CreateProjectController extends AbstractController
{

    public function __construct(
        readonly NormalizerInterface $normalizer,
    )
    {
    }

    #[Route(path:'/projects', name: 'project_create', methods: ['POST'])]
    public function index(Request $request, CreateProjectHandler $handler, ProjectMapper $projectMapper): JsonResponse
    {
        try {
            //TODO check type of return of content
            $projectDTO = $projectMapper->mapRequestToDTO($request->getContent(), CreateProjectDTO::class);
            $handler->handle($projectDTO);

            return $this->json(['success' => 'Project created successfully'], 201);
        } catch (ExceptionInterface $exception) {
            //TODO std status code
            return $this->json(['error' => $exception->getMessage()], 400);
        } catch (\Throwable) {
            return $this->json(['error' => 'Server Error'], 500);
        }
    }
}
