<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\BodyThiefSociety;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BodyThiefSocietyVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $society, mixed $subject): bool
  {
    return in_array($society, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof BodyThiefSociety;
  }

  protected function voteOnAttribute(string $society, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var BodyThiefSociety $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    switch ($society) {
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

  private function canView(BodyThiefSociety $society, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(BodyThiefSociety $society, User $user): bool
  {
    // The storyteller can edit the chronicle socie$society
    if (!is_null($society->getHomebrewFor()) && $user === $society->getHomebrewFor()->getStoryteller()) {
      return true;
    }

    return false;
  }

  private function canDelete(BodyThiefSociety $society, User $user): bool
  {
    // Same rule as for edit
    if ($this->canEdit($society, $user)) {
      return true;
    }

    return false;
  }
}
