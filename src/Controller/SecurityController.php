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
    #[IsGranted('ROLE_ADMIN')]
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
    #[Route('/generate-test-data', name: 'app_generate_test_data')]
    #[IsGranted('ROLE_ADMIN')]
    public function generateTestData(\Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $hasher): \Symfony\Component\HttpFoundation\Response
    {
        // 1. Create Admin
        $admin = new \App\Entity\Admin();
        $admin->setEmail('admin@school.com');
        $admin->setNom('Boss');
        $admin->setPrenom('Admin');
        $admin->setCin(11111111);
        $admin->setMotDePass($hasher->hashPassword($admin, 'admin123'));
        $em->persist($admin);

        // 2. Create Students
        $student1 = new \App\Entity\Etudiant();
        $student1->setEmail('alice@school.com');
        $student1->setNom('Smith');
        $student1->setPrenom('Alice');
        $student1->setCin(22222222);
        $student1->setMotDePass($hasher->hashPassword($student1, 'student123'));
        $em->persist($student1);

        $student2 = new \App\Entity\Etudiant();
        $student2->setEmail('bob@school.com');
        $student2->setNom('Jones');
        $student2->setPrenom('Bob');
        $student2->setCin(33333333);
        $student2->setMotDePass($hasher->hashPassword($student2, 'student123'));
        $em->persist($student2);

        // 3. Create Groups (✅ FIXED: Added setModerateurId)
        $groupA = new \App\Entity\Groupe();
        $groupA->setNom('Symphony Developers');
        $groupA->setDateCreation(new \DateTime());
        $groupA->setModerateurId($admin); // Admin is the moderator here
        $em->persist($groupA);

        $groupB = new \App\Entity\Groupe();
        $groupB->setNom('Database Design Team');
        $groupB->setDateCreation(new \DateTime());
        $groupB->setModerateurId($admin); // Alice is the moderator here
        $em->persist($groupB);

        // 4. Add Memberships
        // (Alice in Group A)
        $mem1 = new \App\Entity\MembreGroupe();
        $mem1->setUserId($student1);
        $mem1->setGroupeD($groupA);
        $mem1->setDateAdhesion(new \DateTime());
        $em->persist($mem1);

        // (Bob in Group B)
        $mem2 = new \App\Entity\MembreGroupe();
        $mem2->setUserId($student2);
        $mem2->setGroupeD($groupB);
        $mem2->setDateAdhesion(new \DateTime());
        $em->persist($mem2);

        // 5. Create Posts
        $post1 = new \App\Entity\Post();
        $post1->setAuteurId($student1);
        $post1->setGroupId($groupA);
        $post1->setContenu('Hello Symphony Developers! This is Alice.');
        $post1->setDateCreation(new \DateTime());
        $em->persist($post1);

        $post2 = new \App\Entity\Post();
        $post2->setAuteurId($student2);
        $post2->setGroupId($groupB);
        $post2->setContenu('Does anyone know how to design a Voter? Bob here.');
        $post2->setDateCreation(new \DateTime());
        $em->persist($post2);

        // Save everything to the database
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('Database seeded successfully! Try logging in with alice@school.com (pass: student123)');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
