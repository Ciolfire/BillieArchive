<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Stores the locale of the user in the session after the
 * login. This can be used by the LocaleSubscriber afterwards.
 */
class UserLocaleSubscriber implements EventSubscriberInterface
{
  public function __construct(
    private RequestStack $requestStack,
  ) {
  }

  public function onLoginSuccess(LoginSuccessEvent $event): void
  {
    /** @var User $user */
    $user = $event->getUser();

    $this->requestStack->getSession()->set('_locale', $user->getLocale());
  }

  public static function getSubscribedEvents(): array
  {
    return [
      LoginSuccessEvent::class => 'onLoginSuccess',
    ];
  }
}
