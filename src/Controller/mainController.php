<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale<%supported_locales%>?%default_locale%}")]
class mainController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index()
    {
      return $this->render('index.html.twig', [
      ]);
    }

    #[Route("/users", name: "users")]
    public function users(UserRepository $userRepository)
    {
      $this->denyAccessUnlessGranted('ROLE_GM');

      $users = $userRepository->findAll();

      return $this->render('user/index.html.twig', [
        'users' => $users,
      ]);
    }

    #[Route("/user/switch/{id<\d+>}/{role}", name: "user_switch_role", methods: ["GET"])]
    public function switchRole(User $user, UserRepository $userRepository, string $role, ManagerRegistry $doctrine)
    {
      $this->denyAccessUnlessGranted('ROLE_GM');

      $user->setRoles([$role]);
      $doctrine->getManager()->flush();

      return $this->redirectToRoute('users');
    }
}