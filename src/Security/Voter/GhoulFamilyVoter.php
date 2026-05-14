<?php

namespace App\Security\Voter;

use App\Entity\GhoulFamily;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GhoulFamilyVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $attribute, mixed $subject): bool
  {
    return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof GhoulFamily;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var GhoulFamily $subject */
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
      case self::EDIT:
        return $this->canEdit($subject, $user);
      case self::VIEW:
        return $this->canView($subject, $user);
      default:
        throw new \LogicException('This code should not be reached!');
    }
  }

  private function canView(GhoulFamily $family, User $user): bool
  {
    $chronicle = $family->getHomebrewFor();
    if ($this->canEdit($family, $user)) {
      return true;
    }

    if ($chronicle && $chronicle->getPlayers()->contains($user)) {
      return true;
    }

    return false;
  }

  private function canEdit(GhoulFamily $family, User $user): bool
  {
    $chronicle = $family->getHomebrewFor();
    // The player own the chronicle
    if ($chronicle && $user === $chronicle->getStoryteller()) {

      return true;
    }

    return false;
  }

  private function canDelete(GhoulFamily $family, User $user): bool
  {
    return $this->canEdit($family, $user);
  }
}
