<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Note;
use App\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class DataService
{
  private $doctrine;
  private $manager;
  private $slugger;

  public function __construct(ManagerRegistry $doctrine, SluggerInterface $slugger)
  {
    $this->doctrine = $doctrine;
    $this->manager = $doctrine->getManager();
    $this->slugger = $slugger;
  }

  public function getConnection()
  {
    return $this->doctrine->getConnection();
  }

  /**
   * Add an entity, will add security checks there
   */
  public function add($entity)
  {
    $this->manager->persist($entity);
  }

  /**
   * Save an entity, will add security checks there
   */
  public function save($entity)
  {
    $this->manager->persist($entity);
    $this->flush();
  }

  /**
   * Remove an entity, will add security checks there
   */
  public function remove($entity)
  {
    $this->manager->remove($entity);
    $this->flush();
  }

  public function flush()
  {
    $this->manager->flush();
  }

  public function reset()
  {
    return $this->doctrine->resetManager();
  }

  public function getRepository($entity)
  {
    return $this->doctrine->getRepository($entity);
  }

  public function find(string $class, $element)
  {

    return $this->doctrine->getRepository($class)->find($element);
  }

  public function findBy(string $class, array $criteria, array $orderBy=null)
  {

    return $this->doctrine->getRepository($class)->findBy($criteria, $orderBy);
  }

  public function findOneBy(string $class, array $criteria)
  {

    return $this->doctrine->getRepository($class)->findOneBy($criteria);
  }

  public function findAll($class)
  {

    return  $this->doctrine->getRepository($class)->findAll();
  }

  public function upload(UploadedFile $file, string $target, string $fileName=null)
  {
    if (is_null($fileName)) {
      $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $safeFilename = $this->slugger->slug($originalFilename);
      $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    }

    try {
      $file->move($target, $fileName);
    } catch (FileException $e) {
      // ... handle exception if something happens during file upload
    }

    return $fileName;
  }

  public function getMeritTypes(Book $book = null)
  {
    $qb = $this->doctrine->getConnection()->createQueryBuilder()
    ->select('type')
    ->from('merits')
    ->groupBy('type')
    ->andWhere("type != ''");
    if (is_null($book)) {
      $result = $qb->executeQuery()->fetchFirstColumn();
    } else {
      $result = $qb->andWhere('book_id = :id')
      ->setParameter('id', $book->getId())
      ->executeQuery()->fetchFirstColumn();
    }
    $result[] = "universal";
    
    return $result;
  }

  public function getBookTypes(string $setting)
  {
    return $this->doctrine->getConnection()->createQueryBuilder()
    ->select('type')
    ->from('book')
    ->where('setting = :setting')
    ->andWhere('type IS NOT NULL')
    ->groupBy('type')
    ->setParameter('setting', $setting)
    ->executeQuery()->fetchFirstColumn();
  }

  public function getChronicleNotesCategory(Chronicle $chronicle, User $user)
  {
    return $this->doctrine->getConnection()->createQueryBuilder()
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

  public function getLinkableNotes(User $user, Note $note)
  {
    return $this->doctrine->getConnection()->createQueryBuilder()
    ->select('id, title')
    ->from('note')
    ->where('chronicle_id = :chronicle')
    ->andWhere('user_id = :user')
    ->andWhere('id != :note')
    ->orderBy('category_id', 'ASC')
    ->setParameter('chronicle', $note->getChronicle()->getId(), Types::INTEGER)
    ->setParameter('user', $user->getId(), Types::INTEGER)
    ->setParameter('note', $note->getId(), Types::INTEGER)
    ->executeQuery()->fetchAllAssociative();
  }
}
