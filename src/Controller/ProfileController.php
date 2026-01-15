<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    public function index()
    {
        return $this->render('profile/profile.html.twig', [
            'player' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em)
    {
        /** @var Player $player */
        $player = $this->getUser();

        $form = $this->createForm(ProfileFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

    $avatarFile = $form->get('avatar')->getData();

        if ($avatarFile) {
            $newFilename = uniqid().'.'.$avatarFile->guessExtension();

            $avatarFile->move(
                $this->getParameter('avatars_directory'),
                $newFilename
            );

            $player->setAvatar($newFilename);
        }

        $em->flush();

        $this->addFlash('success', 'Profil mis à jour');
        return $this->redirectToRoute('app_profile');
    }


        return $this->render('profile/Editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em)
    {
        /** @var Player $player */
        $player = $this->getUser();

        $em->remove($player);
        $em->flush();

        return $this->redirectToRoute('app_logout');
    }
}
