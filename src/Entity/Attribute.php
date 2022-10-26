<?php

namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use League\HTMLToMarkdown\HtmlConverter;


#[Gedmo\TranslationEntity(class: "App\Entity\Translation\AttributeTranslation")]
#[ORM\Entity(repositoryClass: AttributeRepository::class)]
class Attribute
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: \Doctrine\DBAL\Types\Types::INTEGER)]
  private $id;

  #[ORM\Column(type: "string", length: 20)]
  private $identifier;

  #[ORM\Column(type: "string", length: 20)]
  private $category;

  #[ORM\Column(type: "string", length: 20)]
  private $type;

  #[Gedmo\Locale]
  private $locale;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "string", length: 20)]
  private $name;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $description;

  #[Gedmo\Translatable]
  #[ORM\Column(type: "text")]
  private $fluff;

  public function __toString()
  {
    return $this->name;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getIdentifier(): ?string
  {
    return $this->identifier;
  }

  public function setIdentifier(string $identifier): self
  {
    $this->identifier = $identifier;

    return $this;
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

  public function getCategory(): ?string
  {
    return $this->category;
  }

  public function setCategory(string $category): self
  {
    $this->category = $category;

    return $this;
  }

  public function getType(): ?string
  {
    return $this->type;
  }

  public function setType(string $type): self
  {
    $this->type = $type;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $converter = new HtmlConverter();
    $this->description = $converter->convert($description);

    return $this;
  }

  public function getFluff(): ?string
  {
    return $this->fluff;
  }

  public function setFluff(string $fluff): self
  {
    $converter = new HtmlConverter();
    $this->fluff = $converter->convert($fluff);

    return $this;
  }
}
