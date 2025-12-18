<?php

namespace App\Controller\Project;

use App\Dto\Project\GetProjectDTO;
use App\Exception\ExceptionInterface;
use App\Handler\Project\GetProjectByIdHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProjectByIdController extends AbstractController
{
    #[Route(
        path: '/projects/{projectId}',
        name: 'project_get_by_id',
        requirements: ['projectId' => '\d+'],
        methods: ['GET'],
    )]
    public function index(int $projectId, GetProjectByIdHandler $getProjectByIdHandler)
    {
        try {
            $getProjectDTO = GetProjectDTO::from($projectId);
            $responseDTO = $getProjectByIdHandler->handle($getProjectDTO);

            return $this->json($responseDTO, 200);
        } catch (ExceptionInterface $exception) {
            return $this->json(
                ['error' => $exception->getMessage()],
                $exception->getCode()
            );
        } catch (\Throwable) {
            return $this->json(
                ['error' => 'Internal Server Error'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
