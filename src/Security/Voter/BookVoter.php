<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter
{
  public const VIEW = 'view';
  public const EDIT = 'edit';
  public const DELETE = 'delete';

  public function __construct(private Security $security) {}

  protected function supports(string $book, mixed $subject): bool
  {
    return in_array($book, [self::EDIT, self::VIEW, self::DELETE])
      && $subject instanceof Book;
  }

  protected function voteOnAttribute(string $book, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
  {
    /** @var Book $subject */
    $user = $token->getUser();
    if ($this->security->isGranted('ROLE_GM')) {
      return true;
    }

    switch ($book) {
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

  private function canView(Book $book, User $user): bool
  {
    // Anyone can see 
    return true;
  }

  private function canEdit(Book $book, User $user): bool
  {
    return false;
  }

  private function canDelete(Book $book, User $user): bool
  {
    return false;
  }
}
