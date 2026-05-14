<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Virtue;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VirtueVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $virtue, mixed $subject): bool
  {
    return in_array($virtue, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Virtue;
  }

  protected function voteOnAttribute(string $virtue, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Virtue $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    switch ($virtue) {
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

  private function canView(Virtue $virtue, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(Virtue $virtue, User $user): bool
  {
    return false;
  }

  private function canDelete(Virtue $virtue, User $user): bool
  {
    return false;
  }
}
