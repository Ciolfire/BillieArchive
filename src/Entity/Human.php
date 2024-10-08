<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\HumanRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: HumanRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_often")]
class Human extends Character
{
  // #[ORM\Id]
  // #[ORM\GeneratedValue]
  // #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  // protected ?int $id;

  // public function getId() : ?int
  // {
  //   return $this->id;
  // }
}
