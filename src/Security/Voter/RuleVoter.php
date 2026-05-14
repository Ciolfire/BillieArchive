<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Rule;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RuleVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $attribute, mixed $subject): bool
  {
    return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Rule;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Rule $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
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

  private function canView(Rule $rule, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(Rule $rule, User $user): bool
  {
    // The storyteller can edit the chronicle rule
    if (!is_null($rule->getHomebrewFor()) && $user === $rule->getHomebrewFor()->getStoryteller()) {

      return true;
    }

    return false;
  }

  private function canDelete(Rule $rule, User $user): bool
  {
    // Same rule as for edit
    if ($this->canEdit($rule, $user)) {
      return true;
    }

    return false;
  }
}
