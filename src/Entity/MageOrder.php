<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\MageOrderType;
use App\Repository\MageOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "organization_order")]
#[ORM\Entity(repositoryClass: MageOrderRepository::class)]
class MageOrder extends Organization
{
  // #[ORM\ManyToMany(targetEntity: Merit::class, inversedBy: 'covenants')]
  // private Collection $merits;

  // #[ORM\JoinTable(name: 'order_discount_merits')]
  // #[ORM\ManyToMany(targetEntity: Merit::class, inversedBy: 'discountForCovenants')]
  // private Collection $discountMerits;

  /**
   * @var Collection<int, Skill>
   */
  #[ORM\ManyToMany(targetEntity: Skill::class)]
  private Collection $roteSpecialties;

  public function __construct()
  {
    // $this->merits = new ArrayCollection();
    // $this->discountMerits = new ArrayCollection();
    $this->roteSpecialties = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  static public function getType(): string
  {
    return 'order';
  }

  static public function getForm(): string
  {
    return MageOrderType::class;
  }

  // /**
  //  * @return Collection<int, Merit>
  //  */
  // public function getMerits(): Collection
  // {
  //   return $this->merits;
  // }

  // public function addMerit(Merit $merit): static
  // {
  //   if (!$this->merits->contains($merit)) {
  //     $this->merits->add($merit);
  //   }

  //   return $this;
  // }

  // public function removeMerit(Merit $merit): static
  // {
  //   $this->merits->removeElement($merit);

  //   return $this;
  // }

  // /**
  //  * @return Collection<int, Merit>
  //  */
  // public function getDiscountMerits(): Collection
  // {
  //     return $this->discountMerits;
  // }

  // public function addDiscountMerit(Merit $discountMerit): static
  // {
  //     if (!$this->discountMerits->contains($discountMerit)) {
  //         $this->discountMerits->add($discountMerit);
  //     }

  //     return $this;
  // }

  // public function removeDiscountMerit(Merit $discountMerit): static
  // {
  //     $this->discountMerits->removeElement($discountMerit);

  //     return $this;
  // }

  public function getSetting(): string
  {

    return "mage";
  }

  /**
   * @return Collection<int, Skill>
   */
  public function getRoteSpecialties(): Collection
  {
      return $this->roteSpecialties;
  }

  public function addRoteSpecialties(Skill $roteSpecialty): static
  {
      if (!$this->roteSpecialties->contains($roteSpecialty)) {
          $this->roteSpecialties->add($roteSpecialty);
      }

      return $this;
  }

  public function removeRoteSpecialties(Skill $roteSpecialty): static
  {
      $this->roteSpecialties->removeElement($roteSpecialty);

      return $this;
  }
}
