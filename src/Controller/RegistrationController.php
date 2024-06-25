<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
  private EmailVerifier $emailVerifier;

  public function __construct(EmailVerifier $emailVerifier)
  {
    $this->emailVerifier = $emailVerifier;
  }


  #[Route('/{_locale<%supported_locales%>?%default_locale%}/register', name: 'app_register')]
  public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
  {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // encode the plain password
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );

      try {
        $entityManager->persist($user);
        $entityManager->flush();
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation(
          'app_verify_email',
          $user,
          (new TemplatedEmail())
            ->from(new Address('billie@ciolfi.re', '"The Billie"'))
            ->to($user->getEmail())
            ->subject('Billie Archive — Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        $this->addFlash('notice', 'registration.validate.start');
        // do anything else you need here, like send an email

        return $this->redirectToRoute('index');
      } catch (UniqueConstraintViolationException  $e) {
        $this->addFlash('error', 'registration.duplicate');
      }
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }

  // validate email confirmation link, sets User::isVerified=true and persists
  #[Route('/{_locale<%supported_locales%>?%default_locale%}/verify/email', name: 'app_verify_email')]
  public function verifyUserEmail(Request $request, UserRepository $userRepository, Security $security): Response
  {
    // retrieve the user id from the url
    $id = $request->query->get('id');
    // Verify the user id exists and is not null
    if (null !== $id) {
      $user = $userRepository->find($id);
      if (null !== $user && !$user->isVerified()) {
        try {
          $this->emailVerifier->handleEmailConfirmation($request, $user);
          
          $this->addFlash('success', 'registration.validate.success');
          $security->login($user, "security.authenticator.form_login.main");
          $this->addFlash('success', 'registration.validate.login');
          return $this->redirectToRoute('index');
        } catch (VerifyEmailExceptionInterface $exception) {

          $this->addFlash('error', $exception->getReason());
          return $this->redirectToRoute('app_register');
        }
      }
    }
    $this->addFlash('error', "registration.validate.not");
    return $this->redirectToRoute('index');
  }

  #[Route('/{_locale<%supported_locales%>?%default_locale%}/verify/{username}/refresh', name: 'app_refresh_email')]
  public function refreshVerifyEmail(string $username, EntityManagerInterface $entityManager): Response
  {
      $user = $entityManager->getRepository(User::class)->findOneByUsername($username);
      if ($user instanceof User && !$user->isVerified()) {
        $this->emailVerifier->sendEmailConfirmation(
          'app_verify_email',
          $user,
          (new TemplatedEmail())
            ->from(new Address('billie@ciolfi.re', '"The Billie"'))
            ->to($user->getEmail())
            ->subject('Billie Archive — Please Confirm your Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
        );
      }
    $this->addFlash('notice', 'registration.validate.restart');
    return $this->redirectToRoute('index');
  }
}
