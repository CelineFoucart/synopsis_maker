<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Chapter;
use App\Entity\Episode;
use App\Entity\Synopsis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager, 
        protected ValidatorInterface $validator
    ) {
    }
    
    /**
     * @param Synopsis|Category|Chapter|Episode $entity
     * 
     * @return array
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
