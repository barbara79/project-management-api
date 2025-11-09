<?php

namespace App\Controller;

use App\DataMapper\ProjectMapper;
use App\Handler\GetProjectHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class GetProjectsController extends AbstractController
{
    #[Route(
        path: '/projects',
        name: 'project_get_all',
        methods: ['GET'],
        requirements: ['_format' => 'json']
    )]
    public function index(GetProjectHandler $handler, ProjectMapper $mapper): JsonResponse
    {
//        $projects = $handler->handle();
//        $data = $mapper->toArrayList($projects);

        return $this->json('$data');
    }
}
