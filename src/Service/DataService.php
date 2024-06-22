<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\Chronicle;
use App\Entity\Devotion;
use App\Entity\Merit;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class DataService
{
  private ManagerRegistry $doctrine;
  private ObjectManager $manager;
  private SluggerInterface $slugger;

  public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
  {
    $this->doctrine = $doctrine;
    $this->manager = $doctrine->getManager();
    $this->slugger = $slugger;
  }

  public function getDoctrine() : ManagerRegistry
  {
    return $this->doctrine;
  }

  public function getConnection(?string $name = null) : Connection
  {
    /** @var Connection */
    return $this->doctrine->getConnection($name);
  }

  /**
   * Add an entity, will add security checks there
   */
  public function add(object $entity) : void
  {
    $this->manager->persist($entity);
  }

  /**
   * Save an entity, will add security checks there
   */
  public function save(object $entity) : void
  {
    $this->manager->persist($entity);
    $this->flush();
  }

  /**
   * Flag an entity for removal
   */
  public function delete(object $entity) : void
  {
    $this->manager->remove($entity);
  }

  /**
   * Remove an entity, will add security checks there
   */
  public function remove(object $entity) : void
  {
    $this->manager->remove($entity);
    $this->flush();
  }

  public function flush() : void
  {
    $this->manager->flush();
  }

  public function reset() : ObjectManager
  {
    return $this->doctrine->resetManager();
  }

  /**
   * @template T of object
   * @param class-string<T> $entity
   * @return ObjectRepository<object>
   */
  public function getRepository(string $entity) : ObjectRepository
  {
    return $this->doctrine->getRepository($entity);
  }

  /**
   * @template T of object
   * @param class-string<T> $class
   * @return T|null
   */
  public function find(string $class, mixed $id): ?object
  {
    $object = $this->getRepository($class)->find($id);

    if ($object instanceof $class) {
      return $object;
    } else {
      return null;
    }
  }

  /**
   * @template T of object
   * @param class-string<T> $class
   * @param array<string, mixed> $criteria>
   * @return T|null
  */
  public function findOneBy(string $class, array $criteria) : ?object
  {
    $object = $this->getRepository($class)->findOneBy($criteria);
    
    if ($object instanceof $class) {
      return $object;
    } else {
      return null;
    }
  }
  
  /**
   * @param class-string $class
   * @param array<string, mixed> $criteria
   * @param array<string, 'ASC'|'asc'|'DESC'|'desc'>|null $orderBy
   * @return array<int, object>
   */
  public function findBy(string $class, array $criteria, array $orderBy = null): array
  {

    return $this->getRepository($class)->findBy($criteria, $orderBy);
  }

  /**
   * @param class-string $class
   * @return array<int, object>
   */
  public function findAll(string $class) : array
  {

    return  $this->getRepository($class)->findAll();
  }

  public function upload(UploadedFile $file, string $target, string $fileName=null) : ?string
  {
    if (is_null($fileName)) {
      $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $safeFilename = $this->slugger->slug($originalFilename);
      $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    }

    try {
      $file->move($target, $fileName);
    } catch (FileException $e) {

      return null;
      // ... handle exception if something happens during file upload
    }

    return $fileName;
  }

  /** @return array<string> */
  public function getMeritTypes(Book $book = null) : array
  {
    $qb = $this->getConnection()->createQueryBuilder()
    ->select('DISTINCT t.name')
    ->from('merits', 'm')
    ->innerJoin('m', 'content_type', 't', 'm.type_id = t.id')
    ;
    if (!is_null($book)) {
      $qb->andWhere('book_id = :id')
      ->setParameter('id', $book->getId())
      ;
    }
    $result = $qb->executeQuery()->fetchFirstColumn();
    $result[] = "universal";
    
    return $result;
  }

  /** @return array<string> */
  public function getBookTypes(string $setting) : array
  {
    return $this->getConnection()->createQueryBuilder()
    ->select('type')
    ->from('book')
    ->where('setting = :setting')
    ->andWhere('type IS NOT NULL')
    ->groupBy('type')
    ->setParameter('setting', $setting)
    ->executeQuery()->fetchFirstColumn();
  }

  /** @return array<string> */
  public function getChronicleNotesCategory(Chronicle $chronicle, User $user) : array
  {
    return $this->getConnection()->createQueryBuilder()
    ->select('category')
    ->from('note')
    ->where('chronicle_id = :chronicle')
    ->andWhere('user_id = :user')
    ->groupBy('category')
    ->orderBy('category', 'ASC')
    ->setParameter('chronicle', $chronicle->getId())
    ->setParameter('user', $user->getId())
    ->executeQuery()->fetchFirstColumn();
  }

  /**
   * @return array<int, array<string, mixed>>
  **/
  public function getLinkableNotes(User $user, Note $note) : array
  {
    $chronicle = $note->getChronicle();

    if ($chronicle instanceof Chronicle) {
      
      return $this->getConnection()->createQueryBuilder()
      ->select('id, title')
      ->from('note')
      ->where('chronicle_id = :chronicle')
      ->andWhere('user_id = :user')
      ->andWhere('id != :note')
      ->orderBy('category_id', 'ASC')
      ->setParameter('chronicle', $chronicle->getId(), Types::INTEGER)
      ->setParameter('user', $user->getId(), Types::INTEGER)
      ->setParameter('note', $note->getId(), Types::INTEGER)
      ->executeQuery()->fetchAllAssociative();
    } else {

      return [];
    }
  }

  public function loadMeritsPrerequisites(mixed $merits) : void
  {
    if ($merits instanceof PersistentCollection && $merits->current() instanceof CharacterMerit) {
      /** @var CharacterMerit $charMerit */
      foreach ($merits as $charMerit) {
        if ($charMerit->getMerit() instanceof Merit) {
          $this->loadPrerequisites($charMerit->getMerit());
        }
      }
    } else {
      /** @var Merit $merit */
      foreach ($merits as $merit) {
        if ($merit instanceof Merit) {
          $this->loadPrerequisites($merit);
        }
      }
    }
  }

  /**
   * @template T of Merit|Devotion
   * @param T $entity
   */
  public function loadPrerequisites(object $entity) : void
  {
    if (method_exists(get_class($entity), 'getPrerequisites')) {
      foreach ($entity->getPrerequisites() as $prerequisite) {
        $className = $prerequisite->getType();
        if (class_exists($className)) {
          $prereqEntity = $this->findOneBy($className, ['id' => $prerequisite->getEntityId()]);
          if (!is_null($prereqEntity)) {
            $prerequisite->setEntity($prereqEntity);
          }
        }
      }
    }
  }

  public function duplicateCharacter(Character $character, Chronicle $chronicle, User $user) : ?Character
  {
    $newCharacter = clone $character;
    // Updating specific info for the clone
    $newCharacter->setChronicle($this->findOneBy(Chronicle::class, ['id' => $chronicle->getId()]));
    $newCharacter->setPlayer($this->findOneBy(User::class, ['id' => $user->getId()]));
    $newCharacter->setIsPremade(false);
    if ($chronicle->getStoryteller() === $user) {
      $newCharacter->setIsNpc(true);
    }

    try {
      $this->manager->persist($newCharacter);
      // dd($newCharacter);
      $this->manager->flush();
    } catch (\Throwable $th) {
      // dd($th);
      return null;
    }

    return $newCharacter;
  }
}
