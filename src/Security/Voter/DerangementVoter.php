<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Derangement;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DerangementVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $derangement, mixed $subject): bool
  {
    return in_array($derangement, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Derangement;
  }

  protected function voteOnAttribute(string $derangement, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Derangement $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    switch ($derangement) {
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

  private function canView(Derangement $derangement, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(Derangement $derangement, User $user): bool
  {
    // The storyteller can edit the chronicle derangement
    if (!is_null($derangement->getHomebrewFor()) && $user === $derangement->getHomebrewFor()->getStoryteller()) {
      return true;
    }

    return false;
  }

  private function canDelete(Derangement $derangement, User $user): bool
  {
    // Same rule as for edit
    if ($this->canEdit($derangement, $user)) {
      return true;
    }

    return false;
  }
}
