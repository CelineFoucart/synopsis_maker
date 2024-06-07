<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $plainPassword = $form->get('plainPassword')->getData();

            if ($plainPassword !== null) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont bien été modifié');
            
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
