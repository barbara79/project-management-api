<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateProjectController extends AbstractController
{
    #[Route(path:'/projects', name: 'project_create', methods: ['POST'])]
    public function createProject(Request $request, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent());

        if (!$data) {
            return $this->json(['error' => 'Invalid JSON body'], 400);
        }


        $project = new Project();
        $project->setTitle($data->title);
        $project->setDescription($data->description ?? null);
        $project->setDeadline(new \DateTime($data->deadline));
        $project->setOwner($data->owner);

        // Data validation
        $errors = $validator->validate($project);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $messages], 400);
        }

        $em->persist($project);
        $em->flush();

        return $this->json([
            'project' => $project,
            'message' => 'project created successfully'
        ], 201);
    }
}
