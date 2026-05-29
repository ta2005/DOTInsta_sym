<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Form\ControleType;
use App\Repository\ControleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/controle')]
final class ControleController extends AbstractController
{
    #[Route(name: 'app_controle_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(ControleRepository $controleRepository): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $controles = $controleRepository->findAll();
        } else if ($this->isGranted('ROLE_ENSEIGNANT')) {
             $controles = $controleRepository->createQueryBuilder('c')
             ->join('c.enseignement_id', 'e')
             ->where('e.professeur_id = :prof')
             ->setParameter('prof', $user)
             ->getQuery()->getResult();
        } else {
             $controles = $controleRepository->findBy(['etudiant_id' => $user]);
        }
        return $this->render('controle/index.html.twig', [
            'controles' => $controles,
        ]);
    }

    #[Route('/new', name: 'app_controle_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $controle = new Controle();
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($controle);
            $entityManager->flush();

            return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('controle/new.html.twig', [
            'controle' => $controle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controle_show', methods: ['GET'])]
    #[IsGranted('CONTROLE_VIEW', subject: 'controle')]
    public function show(Controle $controle): Response
    {
        return $this->render('controle/show.html.twig', [
            'controle' => $controle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_controle_edit', methods: ['GET', 'POST'])]
    #[IsGranted('CONTROLE_EDIT', subject: 'controle')]
    public function edit(Request $request, Controle $controle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ControleType::class, $controle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('controle/edit.html.twig', [
            'controle' => $controle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controle_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Controle $controle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$controle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($controle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_controle_index', [], Response::HTTP_SEE_OTHER);
    }
}
