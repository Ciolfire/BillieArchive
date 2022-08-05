<?php

namespace App\Entity;

use App\Repository\AttributeRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use App\Entity\Translation\AttributeTranslation;
use League\HTMLToMarkdown\HtmlConverter;

/**
 * @ORM\Entity(repositoryClass=AttributeRepository::class)
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\AttributeTranslation")
 */
class Attribute implements Translatable
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=20)
   */
  private $identifier;

  /**
   * @ORM\Column(type="string", length=20)
   */
  private $category;

  /**
   * @ORM\Column(type="string", length=20)
   */
  private $type;

  /**
   * @Gedmo\Locale
   * Used locale to override Translation listener`s locale
   * this is not a mapped field of entity metadata, just a simple property
   */
  private $locale;

  /**
   * @ORM\Column(type="string", length=20)
   * @Gedmo\Translatable
   */
  private $name;

  /**
   * @ORM\Column(type="text"))
   * @Gedmo\Translatable
   */
  private $description;

  /**
   * @ORM\Column(type="text")
   * @Gedmo\Translatable
   */
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
