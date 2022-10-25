<?php

namespace App\Service;

use Doctrine\Common\Collections\Criteria;
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

  public function find(string $class, $element)
  {

    return $this->doctrine->getRepository($class)->find($element);
  }

  public function findBy(string $class, array $criteria)
  {

    return $this->doctrine->getRepository($class)->findBy($criteria);
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
}
