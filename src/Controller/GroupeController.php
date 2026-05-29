<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\MembreGroupe;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//only an admin must have access to this route

use App\Entity\User;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/groupe')]
final class GroupeController extends AbstractController
{
    #[Route(name: 'app_groupe_index', methods: ['GET'])]
    public function index(GroupeRepository $groupeRepository): Response
    {
        return $this->render('groupe/index.html.twig', [
            'groupes' => $groupeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_groupe_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupe->setDateCreation(new \DateTime());
            $groupe->setModerateurId($this->getUser());
            $entityManager->persist($groupe);
            $entityManager->flush();

            return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe/new.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_show', methods: ['GET'])]
    public function show(Groupe $groupe, \App\Repository\MembreGroupeRepository $membreGroupeRepository): Response
    {
        $memberships = $membreGroupeRepository->findBy(['groupe_d' => $groupe]);

        return $this->render('groupe/show.html.twig', [
            'groupe' => $groupe,
            'memberships' => $memberships,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_groupe_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($groupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('{id}/add-user/{userId}',name: 'app_ajouter_user_groupe')]
    #[IsGranted('ROLE_ADMIN')]
    public function join(Groupe $groupe,
                        #[MapEntity(id: 'userId')] User $user,
                        EntityManagerInterface $entityManager):Response{
        $membreGroupe  = new MembreGroupe();
        $membreGroupe->setDateAdhesion(new \DateTime());
        $membreGroupe->setUserId($user);
        $membreGroupe->setGroupeD($groupe);
        $entityManager->persist($membreGroupe);
        $entityManager->flush();

        return $this->redirectToRoute('app_groupe_show', ['id' => $groupe->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add-member-list', name: 'app_groupe_add_membre_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addMembreList(Groupe $groupe, \App\Repository\UserRepository $userRepository, \App\Repository\MembreGroupeRepository $membreGroupeRepository): Response
    {
        $allUsers = $userRepository->findAll();
        $memberships = $membreGroupeRepository->findBy(['groupe_d' => $groupe]);
        
        $memberIds = [];
        foreach ($memberships as $m) {
            $memberIds[] = $m->getUserId()->getId();
        }
        
        $nonMembers = [];
        foreach ($allUsers as $user) {
            if (!in_array($user->getId(), $memberIds)) {
                $nonMembers[] = $user;
            }
        }

        return $this->render('groupe/add_membre_list.html.twig', [
            'groupe' => $groupe,
            'nonMembers' => $nonMembers,
        ]);
    }

    #[Route('{id}/remove-user/{userId}',name: 'app_retirer_user_groupe')]
    #[IsGranted('ROLE_ADMIN')]
    public function removeUser(Groupe $groupe,
                        #[MapEntity(id: 'userId')] User $user,
                        EntityManagerInterface $entityManager,
                        \App\Repository\MembreGroupeRepository $membreGroupeRepository):Response{
        $membership = $membreGroupeRepository->findOneBy(['groupe_d' => $groupe, 'user_id' => $user]);
        if ($membership) {
            $entityManager->remove($membership);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_groupe_show', ['id' => $groupe->getId()], Response::HTTP_SEE_OTHER);
    }
}
