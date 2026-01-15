<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $player = new Player();
        $form = $this->createForm(RegistrationFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $player->setPassword(
                $passwordHasher->hashPassword($player, $player->getPassword())
            );
            $player->setCreatedAt(new \DateTimeImmutable());

            // Upload avatar
            $avatarFile = $form->get('avatar')->getData();

        if ($avatarFile) {
            $avatarsDir = $this->getParameter('avatars_directory');
            $newFilename = uniqid().'.'.$avatarFile->guessExtension();

        try {
            $avatarFile->move($avatarsDir, $newFilename);
            $player->setAvatar($newFilename);
        } catch (FileException $e) {
            $this->addFlash('warning', 'Erreur lors de l’upload de l’avatar');
        }
    }


            $em->persist($player);
            $em->flush();

            $this->addFlash('success', 'Compte créé avec succès !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
