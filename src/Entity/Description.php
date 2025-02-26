<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\DescriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DescriptionRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Description
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private ?int $id = null;

  #[ORM\Column(type: "string", length: 50)]
  private string $name;

  #[ORM\Column(type: "text")]
  private string $value = "";

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getValue(): ?string
  {
    return $this->value;
  }

  public function setValue(string $value): self
  {
    $this->value = $value;

    return $this;
  }
}
