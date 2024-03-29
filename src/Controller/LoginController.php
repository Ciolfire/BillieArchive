<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  #[Route("/{_locale<%supported_locales%>?%default_locale%}/login", name:"login")]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    if (!is_null($this->getUser())) {
      return $this->redirectToRoute('index');
    }
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  #[Route("/logout", name:"logout", methods:["GET"])]
  public function logout(): void
  {
      // controller can be blank: it will never be called!
      throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
