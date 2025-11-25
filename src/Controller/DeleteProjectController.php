<?php

namespace App\Controller;

use App\Dto\GetProjectDTO;
use App\Exception\ExceptionInterface;
use App\Handler\DeleteProjectByIdHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProjectController extends AbstractController
{
    #[Route(
        '/projects/{projectId}',
        name: 'project_delete',
        requirements: ['projectId' => '\d+'],
        methods: ['DELETE']
    )]
    public function index(int $projectId, DeleteProjectByIdHandler $deleteProjectByIdHandler, EntityManagerInterface $em)
    {

        try {
            $projectDTO = GetProjectDTO::from($projectId);
            $deleteProjectByIdHandler->handle($projectDTO);

            return $this->json(['success' => 'project deleted'], JsonResponse::HTTP_OK);
        } catch (ExceptionInterface $exception) {
            return $this->json(
                ['error' => $exception->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (\Throwable $throwable) {
            return $this->json(
                ['error' => 'Internal Server Error'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }


    }
}
