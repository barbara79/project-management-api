<?php

namespace App\Validator;


use App\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateProjectValidator implements ProjectValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    { }

    public function projectValidator(Project $projectMapped): void
    {
        $errors = $this->validator->validate($projectMapped);

        if (count($errors) > 0) {
            $messages = [];

            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $messages[] = sprintf(
                    '%s: %s',
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }

            throw new \InvalidArgumentException(implode(', ', $messages));
        }
    }

}
