<?php

namespace App\Controller\Project;

use App\Exception\ExceptionInterface;
use App\Handler\Project\ListProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ListProjectController extends AbstractController
{
    #[Route(
        path: '/projects',
        name: 'projects_list',
        methods: ['GET'],
    )]
    public function index(ListProjectHandler $listProjectHandler)
    {
        try {
            $responseDTO = $listProjectHandler->handle();

            return  $this->json($responseDTO, 200);
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
