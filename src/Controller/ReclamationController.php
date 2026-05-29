<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Controle;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Enum\StatutReclamationEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route(name: 'app_reclamation_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $reclamations = $reclamationRepository->findAll();
        } else if ($this->isGranted('ROLE_ENSEIGNANT')) {
             $reclamations = $reclamationRepository->createQueryBuilder('r')
             ->join('r.controle_id', 'c')
             ->join('c.enseignement_id', 'e')
             ->where('e.professeur_id = :prof')
             ->setParameter('prof', $user)
             ->getQuery()->getResult();
        } else {
             $reclamations = $reclamationRepository->createQueryBuilder('r')
             ->join('r.controle_id', 'c')
             ->where('c.etudiant_id = :etudiant')
             ->setParameter('etudiant', $user)
             ->getQuery()->getResult();
        }
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ETUDIANT')]
    public function new(Request $request, Controle $controle, EntityManagerInterface $entityManager): Response
    {
        if ($controle->getEtudiantId() !== $this->getUser()) {
             throw $this->createAccessDeniedException();
        }

        $reclamation = new Reclamation();
        $reclamation->setDateCreation(new \DateTime());
        $reclamation->setControleId($controle);
        $reclamation->setStatut(StatutReclamationEnum::EN_ATTENTE);

        $form = $this->createForm(ReclamationType::class, $reclamation);
        // We might need to remove controle_id from the form to prevent users from changing it
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    #[IsGranted('RECLAMATION_VIEW', subject: 'reclamation')]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    #[IsGranted('RECLAMATION_EDIT', subject: 'reclamation')]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    #[IsGranted('RECLAMATION_DELETE', subject: 'reclamation')]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
