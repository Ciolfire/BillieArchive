<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
  private VerifyEmailHelperInterface $verifyEmailHelper;
  private MailerInterface $mailer;
  private EntityManagerInterface $entityManager;

  public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
  {
    $this->verifyEmailHelper = $helper;
    $this->mailer = $mailer;
    $this->entityManager = $manager;
  }

  public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
  {
    /** @var User $user */
    $signatureComponents = $this->verifyEmailHelper->generateSignature(
      $verifyEmailRouteName,
      (string)$user->getId(),
      $user->getEmail(),
      ['id' => $user->getId()], // add the user's id as an extra query param
    );
    dd($signatureComponents);
    $context = $email->getContext();
    $context['signedUrl'] = $signatureComponents->getSignedUrl();
    $context['user'] = $user;
    $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
    $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

    $email->context($context);

    $this->mailer->send($email);
  }

  /**
   * @throws VerifyEmailExceptionInterface
   */
  public function handleEmailConfirmation(Request $request, UserInterface $user): void
  {
    /** @var User $user */
    $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string)$user->getId(), $user->getEmail());

    $user->setIsVerified(true);

    $this->entityManager->persist($user);
    $this->entityManager->flush();
  }
}
