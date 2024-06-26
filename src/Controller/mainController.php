<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale<%supported_locales%>?%default_locale%}")]
class mainController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/", name: "index")]
  public function index() : Response
  {
    return $this->render('index.html.twig', [
    ]);
  }

  #[Route("/users", name: "users")]
  public function users() : Response
  {
    $this->denyAccessUnlessGranted('ROLE_GM');

    $users = $this->dataService->findBy(User::class, [], ['username' => 'ASC']);

    return $this->render('user/index.html.twig', [
      'users' => $users,
    ]);
  }

  #[Route("/user/switch/{id<\d+>}/{role}", name: "user_switch_role", methods: ["GET"])]
  public function switchRole(User $user, string $role) : Response
  {
    $this->denyAccessUnlessGranted('ROLE_GM');

    $user->setRoles([$role]);
    $this->dataService->flush();

    return $this->redirectToRoute('users');
  }

  #[Route("/user/{id<\d+>}/activate", name: "user_activate", methods: ["GET"])]
  public function userActivate(User $user) : Response
  {
    $this->denyAccessUnlessGranted('ROLE_GM');

    if ($user->isVerified()) {
      $user->setIsVerified(false);
    } else {
      $user->setIsVerified(true);
    }
    $this->dataService->flush();

    return $this->redirectToRoute('users');
  }

  // #[Route('/check/template', name: 'check_template')]
  // public function seeTemplate(): Response
  // {
  //   return $this->render('registration/confirmation_email.html.twig', [
  //     'signedUrl' => "",
  //     'expiresAtMessageKey' => "",
  //     'expiresAtMessageData' => [],
  //   ]);
  // }
}