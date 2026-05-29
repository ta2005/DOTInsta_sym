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
use Symfony\Component\Security\Http\Attribute\IsGranted;

//a normal user must have acces to create demande
//and me/status
//i need to make the app_demande_index route for the admin to filter demande
//based on type whether they concern(he changed their status) him or not

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
    #[IsGranted('ROLE_USER')]
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

            return $this->redirectToRoute('get_my_demande', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande/new_demande.html.twig', [
            /* 'demande' => $demande, */
            'form' => $form,
        ]);
    }

    #[Route('/{id}/change/{status}',name:'change_status', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function changeStatus(Request $request, Demande $demande,EntityManagerInterface $em,string $status="accepte"){
        if (!$this->isCsrfTokenValid('change'.$demande->getId(), $request->getPayload()->getString('_token'))) {
            return $this->redirectToRoute("app_demande_index");
        }
        if($status=="accepte"){
            $demande->setStatut(RequeteEnum::ACCEPTEE);
        }else if($status=="refusee"){
            $demande->setStatut(RequeteEnum::REFUSEE);
        }else{
            $this->addFlash('error', 'Action non reconnue.');
            return $this->redirectToRoute("app_demande_index");
        }
        $demande->setAdminId($this->getUser());
        $em->flush();

        $this->addFlash('success', 'Le statut de la demande a été mis à jour.');
        return $this->redirectToRoute("app_demande_index");
    }

    #[Route('/me/{statut}',name:'get_my_demande')]
    #[IsGranted('ROLE_USER')]
    public function getMyDemande(EntityManagerInterface $em,?string $statut=null){
        $crit = ["user_id" => $this->getUser()];
        if($statut!==null){
            $e = RequeteEnum::tryFrom($statut);
            if(!$e){
                throw $this->createNotFoundException("status invalid");
            }
            $crit["statut"] = $e;
        }

        return $this->render('demande/index.html.twig', [
        'demandes' => $em->getRepository(Demande::class)->findBy($crit)
        ]);
    }

    #[Route('/{id}', name: 'app_demande_show', methods: ['GET'])]
    #[IsGranted('DEMANDE_VIEW', subject: 'demande')]
    public function show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
        'demande' => $demande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_demande_edit', methods: ['GET', 'POST'])]
    #[IsGranted('DEMANDE_EDIT', subject: 'demande')]
    public function edit(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('get_my_demande', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('demande/edit.html.twig', [
        'demande' => $demande,
        'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_demande_delete', methods: ['POST'])]
    #[IsGranted('DEMANDE_DELETE', subject: 'demande')]
    public function delete(Request $request, Demande $demande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}
