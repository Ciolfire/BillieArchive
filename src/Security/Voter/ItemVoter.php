<?php

namespace App\Security\Voter;

use App\Entity\Item;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ItemVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $attribute, mixed $subject): bool
  {
    return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Item;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Item $subject */
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

  private function canView(Item $item, User $user): bool
  {
    return true;
    if ($this->canEdit($item, $user)) {
      return true;
    }

    if ($item->isShared()) {
      return true;
    }

    return false;
  }

  private function canEdit(Item $item, User $user): bool
  {
    $chronicle = $item->getHomebrewFor();
    // The player own the chronicle
    if ($chronicle && $user === $chronicle->getStoryteller()) {

      return true;
    }
    
    // The player's character own the item
    if ($item->getOwner() && $item->getOwner()->getPlayer() === $user) {

      return true;
    }

    return false;
  }

  private function canDelete(Item $item, User $user): bool
  {
    return $this->canEdit($item, $user);
  }
}
