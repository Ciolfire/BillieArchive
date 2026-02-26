<?php

namespace App\Entity;

use App\Entity\Traits\Homebrewable;
use App\Entity\Traits\Sourcable;

use App\Repository\GiftListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: GiftListRepository::class)]
#[ORM\AssociationOverrides([new ORM\AssociationOverride(name: "book", inversedBy: "giftLists"),new ORM\AssociationOverride(name: "homebrewFor", inversedBy: "giftLists")])]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\GiftListTranslation")]
class GiftList implements Translatable
{
  use Sourcable;
  use Homebrewable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $short = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $rules = null;

  /**
   * @var Collection<int, Gift>
   */
  #[ORM\OneToMany(targetEntity: Gift::class, mappedBy: 'list', orphanRemoval: true)]
  #[ORM\OrderBy(["level" => "ASC"])]
  private Collection $gifts;

  #[ORM\Column]
  private ?bool $isCommon = null;

  public function __construct()
  {
      $this->gifts = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getShort(): ?string
  {
    return $this->short;
  }

  public function setShort(string $short): static
  {
    $this->short = $short;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $description): static
  {
    if ($this->description == "") {
      $this->description = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $description);
    } else {
      $this->description = $description;
    }

    return $this;
  }

  public function getRules(): ?string
  {
    return $this->rules;
  }

  public function setRules(string $rules): static
  {
    if ($this->rules == "") {
      $this->rules = preg_replace("/(?<!(\n))\n(?!\n)/m", " ", $rules);
    } else {
      $this->rules = $rules;
    }

    return $this;
  }

  /**
   * @return Collection<int, Gift>
   */
  public function getGifts(): Collection
  {
      return $this->gifts;
  }

  public function addGift(Gift $gift): static
  {
      if (!$this->gifts->contains($gift)) {
          $this->gifts->add($gift);
          $gift->setList($this);
      }

      return $this;
  }

  public function removeGift(Gift $gift): static
  {
      if ($this->gifts->removeElement($gift)) {
          // set the owning side to null (unless already changed)
          if ($gift->getList() === $this) {
              $gift->setList(null);
          }
      }

      return $this;
  }

  public function isCommon(): ?bool
  {
      return $this->isCommon;
  }

  public function setIsCommon(bool $isCommon): static
  {
      $this->isCommon = $isCommon;

      return $this;
  }
}
