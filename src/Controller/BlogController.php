<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog')]
#[IsGrnated('ROLE_USER')]
final class BlogController extends AbstractController
{
   #[Route('/', name: 'app_blog_feed')]
   public function index(PostRepository $postRepository): Response
   {
      $user = $this->getUser();
      if ($this->isGranted('ROLE_ADMIN')) {
          $posts = $postRepository->findBy([], ['date_creation' => 'DESC']);
      }
      else {
         $posts = $postRepository->findFeedForUser($user);
      }
      return $this->render('blog/feed.html.twig', [
         'posts' => $posts,
      ]);
   }
}
