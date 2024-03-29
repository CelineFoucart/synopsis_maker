<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Episode;
use App\Entity\Synopsis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator
    ) {
    }

    /**
     * @param Synopsis|Category|Chapter|Episode $entity
     */
    protected function validate($entity): array
    {
        $errors = [];
        $violations = $this->validator->validate($entity);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        return $errors;
    }
}
