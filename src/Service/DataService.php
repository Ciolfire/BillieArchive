<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\Chronicle;
use App\Entity\ContentType;
use App\Entity\Covenant;
use App\Entity\Devotion;
use App\Entity\Merit;
use App\Entity\Note;
use App\Entity\Organization;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Cache;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class DataService
{
  private ManagerRegistry $doctrine;
  private ObjectManager $manager;
  private SluggerInterface $slugger;
  private Cache $cache;
  private array $genericTypes = [];

  public function __construct(EntityManagerInterface $em, ManagerRegistry $doctrine, SluggerInterface $slugger)
  {
    $this->cache = $em->getCache();
    $this->doctrine = $doctrine;
    $this->manager = $doctrine->getManager();
    $this->slugger = $slugger;
    $this->genericTypes = $this->findBy(ContentType::class,
      [
        'name' => [
          'human',
          'blood_bather',
          'body_thief',
          'purified',
          'possessed',
          'innocents',
          'feral',
          'psychic',
          'thaumaturge',
        ]
      ],
      ['name' => 'ASC']
    );
    $this->genericTypes[] = null;
    // dd($this->getGenericTypes());
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
    if ($entity instanceof Character) {
      $entity->setPowerRating();
    }
    $this->manager->persist($entity);
  }

  /**
   * Save an entity, will add security checks there
   */
  public function save(object $entity) : void
  {
    if ($entity instanceof Character) {
      $entity->setPowerRating();
    }
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

  public function update(object $entity) : void
  {
    if ($entity instanceof Character) {
      $entity->setPowerRating();
    }
    $this->cache->evictEntity($entity::class, $entity->getId());
    $this->manager->flush();
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

  public function upload(UploadedFile $file, string $target, string $filename=null) : ?string
  {
    if (is_null($filename)) {
      $originalFilename = pathinfo($file->getBasename(), PATHINFO_FILENAME);
    } else {
      $originalFilename = $filename;
    }
    $safeFilename = $this->slugger->slug($originalFilename);
    $filename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

    try {
      $file->move($target, $filename);
    } catch (FileException $e) {

      return null;
      // ... handle exception if something happens during file upload
    }

    return $filename;
  }

  public function removeFile(string $filename): bool
  {
    $filesystem = new Filesystem();
    try {
      $filesystem->remove($filename);
    } catch (\Throwable $th) {

      return false;
    }

    return true;
  }

  public function getList(string $type, int $id, $class, $method): Collection|array
  {
    $repo = $this->getRepository($class);

    switch ($type) {
      case 'book':
        /** @var Book */
        $book = $this->findOneBy(Book::class, ['id' => $id]);
        $items = $book->$method();
        break;
      default:
        $items = $repo->findAll();
        break;
    }

    return $items;
  }

  public function getItemFromType(string $type, $id) : array
  {
    switch ($type) {
      case 'chronicle':
        /** @var Chronicle */
        $item = $this->findOneBy(Chronicle::class, ['id' => $id]);
        $back = ['path' => 'homebrew_index', 'params' => ['id' => $id]];
        break;
      case 'book':
      default:
        /** @var Book */
        $item = $this->findOneBy(Book::class, ['id' => $id]);
        $back = ['path' => 'book_index', 'params' => ['id' => $id]];
    }

    return [
      'item' => $item,
      'back' => $back
    ];
  }

  public function getGenericTypes() : array
  {
    return $this->genericTypes;
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
        if (class_exists($className) && $prerequisite->getId()) {
          $prereqEntity = $this->findOneBy($className, ['id' => $prerequisite->getEntityId()]);
          if (!is_null($prereqEntity)) {
            $prerequisite->setEntity($prereqEntity);
          }
        }
      }
    }
  }

  public function getOrganizations(?string $setting)
  {
    switch ($setting) {
      case 'vampire':

        return $this->findBy(Covenant::class, ['homebrewFor' => null], ['name' => 'ASC']);
      default:

        return $this->findBy(Organization::class, ['name' => $this->genericTypes]);
    }
  }

  public function duplicateCharacter(Character $character, ?Chronicle $chronicle, User $user) : ?Character
  {
    $id = $character->getId();
    $this->manager->detach($character);
    $character = $this->manager->find(Character::class, $id);
    // Tried this to bypass the fetch: EAGER, no luck, maybe try other stuff
    // $character->getAttributes();
    // $character->getSkills();
    $newCharacter = clone $character;
    // Updating specific info for the clone
    $newCharacter->setChronicle($chronicle);
    $newCharacter->setPlayer($this->findOneBy(User::class, ['id' => $user->getId()]));
    $newCharacter->setIsPremade(false);
    if ($chronicle instanceof Chronicle && $chronicle->getStoryteller() === $user) {
      $newCharacter->setIsNpc(true);
    }

    try {
      $this->manager->persist($newCharacter);
      $this->manager->flush();
    } catch (\Throwable $th) {
      // dd($th, $newCharacter, $newCharacter->getId());
      if (!is_null($newCharacter->getId())) {
        return $newCharacter;
      }
      return null;
    }

    return $newCharacter;
  }
}
