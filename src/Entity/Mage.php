<?php

namespace App\Entity;

use App\Repository\MageRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MageRepository::class)]
class Mage extends Character
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  protected $id;

  public function getId(): ?int
  {
    return $this->id;
  }
}