<?php

namespace App\Security\Voter;

use App\Entity\Chronicle;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChronicleVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $attribute, mixed $subject): bool
  {
    return in_array($attribute, [self::EDIT, self::VIEW])
      && $subject instanceof Chronicle;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    /** @var Chronicle $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }
    if (!$user instanceof User) {
      // if the user is anonymous, do not grant access
      return false;
    }

    switch ($attribute) {
      case self::DELETE:
        return $this->canDelete($subject, $user);
        break;
      case self::EDIT:
        return $this->canEdit($subject, $user);
        break;
      case self::VIEW:
        return $this->canView($subject, $user);
        break;
      default:
        throw new \LogicException('This code should not be reached!');
    }

    return false;
  }

  private function canView(Chronicle $chronicle, User $user): bool
  {
    if ($this->canEdit($chronicle, $user)) {
      return true;
    }

    if ($chronicle->getPlayers()->contains($user)) {
      return true;
    }

    return false;
  }

  private function canEdit(Chronicle $chronicle, User $user): bool
  {
    // The player own the chronicle
    if ($user === $chronicle->getStoryteller()) {

      return true;
    }

    return false;
  }

  private function canDelete(Chronicle $chronicle, User $user): bool
  {
    return $this->canEdit($chronicle, $user);
  }
}
