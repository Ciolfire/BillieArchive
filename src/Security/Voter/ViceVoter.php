<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Vice;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ViceVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $vice, mixed $subject): bool
  {
    return in_array($vice, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Vice;
  }

  protected function voteOnAttribute(string $vice, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Vice $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    switch ($vice) {
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

  private function canView(Vice $vice, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(Vice $vice, User $user): bool
  {
    return false;
  }

  private function canDelete(Vice $vice, User $user): bool
  {
    return false;
  }
}
