<?php

namespace App\Controller;

use App\Enum\RequeteEnum;
use App\Entity\Demande;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demande')]
final class DemandeController extends AbstractController
{
    #[Route(name: 'app_demande_index', methods: ['GET'])]
    public function index(DemandeRepository $demandeRepository): Response
    {
        return $this->render('demande/index.html.twig', [
            'demandes' => $demandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setDateCreation(new \DateTime());
            $demande->setUserId($this->getUser());
            $demande->setStatut(RequeteEnum::EN_ATTENTE);
            $entityManager->persist($demande);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande/new_demande.html.twig', [
            /* 'demande' => $demande, */
            'form' => $form,
        ]);
    }
    #[Route('/{id}/change/{status}',name:'change_status')]
    public function changeSatus(Demande $demande,EntityManagerInterface $em,string $status="accepte"){
       if($status=="accepte"){
          $demande->setStatus(RequeteEnum::ACCEPTEE);
          $demande->setAdminId($this->getUser());
       }else if($status=="refusee"){
          $demande->setStatus(RequeteEnum::REFUSEE);
          $demande->setAdminId($this->getUser());
       }
       $em->flush();

       return $this->redirectToRoute("app_demande_index");

    }

    #[Route('/me/{statut}',name:'get_my_demande')]
    public function getMyDemande(EntityManagerInterface $em,?string $statut=null){
        if($statut==null){
            $crit = ["user_id" => $this->getUser()];
        }else{
            $crit = ["user_id" => $this->getUser(),"statut"=>$statut];
        }
        return $this->render('demande/index.html.twig', [
        'demandes' => $em->getRepository(Demande::class)->findBy($crit)
        ]);

    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    public function show(Demande $demande): Response
    {
       return $this->render('demande/show.html.twig', [
       'demande' => $demande,
       ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
       $form = $this->createForm(DemandeType::class, $demande);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->flush();

          return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
       }

       return $this->render('demande/edit.html.twig', [
       'demande' => $demande,
       'form' => $form,
       ]);
    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    public function delete(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
       if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->getPayload()->getString('_token'))) {
          $entityManager->remove($demande);
          $entityManager->flush();
       }

       return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }
 }
