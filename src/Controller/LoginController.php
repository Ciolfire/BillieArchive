<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  #[Route('/')]
  public function indexNoLocale(): Response
  {
    return $this->redirectToRoute('index');
  }

  #[Route("/{_locale<%supported_locales%>?%default_locale%}/login", name:"login")]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    if (!is_null($this->getUser())) {
      return $this->redirectToRoute('index');
    }
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    if ($error instanceof CustomUserMessageAccountStatusException) {
      if ($error->getMessage() == "exception.verified") {
        $link = [
          "href" => $this->generateUrl("app_refresh_email", ['username' => $lastUsername]),
          "text" => "register.resend",
        ];
      }
    } else {
      $link = null;
    }

    return $this->render('login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
      'link' => $link,
    ]);
  }

  #[Route("/logout", name:"logout", methods:["GET"])]
  public function logout(): void
  {
    // controller can be blank: it will never be called!
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
