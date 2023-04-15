<?php

namespace App\Security\Voter;

use App\Entity\Character;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CharacterVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';
  private $security;

  public function __construct(Security $security)
  {
      $this->security = $security;
  }
  protected function supports(string $attribute, mixed $subject): bool
  {
    // if the attribute isn't one we support, return false
    if (!in_array($attribute, [self::VIEW, self::EDIT])) {
      return false;
    }

    // only vote on `Character` objects
    if (!$subject instanceof Character) {
      return false;
    }

    return true;
  }

  protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
  {
    $user = $token->getUser();

    if (!$user instanceof User) {
      // the user must be logged in; if not, deny access
      return false;
    }

    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    // you know $subject is a Character object, thanks to `supports()`
    /** @var Character $post */
    $character = $subject;

    return match($attribute) {
      self::VIEW => $this->canView($character, $user),
      self::EDIT => $this->canEdit($character, $user),
      self::DELETE => $this->canDelete($character, $user),
      default => throw new \LogicException('This code should not be reached!')
    };
  }

  private function canView(Character $character, User $user): bool
  {
    if ($this->canEdit($character, $user)) {
      return true;
    }

    if ($character->isTemplate()) {
      return true;
    }
  }

  private function canEdit(Character $character, User $user): bool
  {
    if ($user === $character->getPlayer()) {

      return true;
    }
    if (!is_null($character->getChronicle()) && $user === $character->getChronicle()->getStoryteller()) {

      return true;
    }

    return false;
  }

  private function canDelete(Character $character, User $user): bool
  {
    if ($user === $character->getPlayer()) {

      return true;
    }

    return false;
  }
}
