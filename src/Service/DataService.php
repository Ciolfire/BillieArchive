<?php

namespace App\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

class DataService
{
  private $doctrine;
  private $manager;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
    $this->manager = $doctrine->getManager();
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
}