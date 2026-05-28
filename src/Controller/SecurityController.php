<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/setup-admin', name: 'setup_admin')]
    public function setupAdmin(EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $admin = new \App\Entity\Admin();
        $admin->setEmail('admin@test.com');
        $admin->setNom('System');
        $admin->setPrenom('Admin');
        $admin->setCin(12345678);

        // Hash the password 'admin123'
        $hashedPassword = $hasher->hashPassword($admin, 'admin123');
        $admin->setMotDePass($hashedPassword);

        $em->persist($admin);
        $em->flush();

        return new Response('Admin created! You can now login at /login with admin@test.com / admin123');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
