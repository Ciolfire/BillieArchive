<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\MageRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: MageRepository::class)]
class Mage extends Character
{
  // #[ORM\Id]
  // #[ORM\GeneratedValue]
  // #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  // protected int $id;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getType(): string
  {
    return "mage";
  }

  public function getForm(): string
  {
    return "none";
    // return MageType::class;
  }
}