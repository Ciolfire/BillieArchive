<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CovenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "organization_covenant")]
#[ORM\Entity(repositoryClass: CovenantRepository::class)]
class Covenant extends Organization
{
  /**
   * @var Collection<int, Discipline>
   */
  #[ORM\ManyToMany(targetEntity: Discipline::class, inversedBy: 'covenants')]
  private Collection $disciplines;

  /**
   * @var Collection<int, Devotion>
   */
  #[ORM\ManyToMany(targetEntity: Devotion::class, inversedBy: 'covenants')]
  private Collection $devotions;

  #[ORM\ManyToMany(targetEntity: Merit::class, inversedBy: 'covenants')]
  private Collection $merits;

  #[ORM\JoinTable(name: 'covenant_discount_merits')]
  #[ORM\ManyToMany(targetEntity: Merit::class, inversedBy: 'discountForCovenants')]
  private Collection $discountMerits;

  public function __construct()
  {
    $this->disciplines = new ArrayCollection();
    $this->devotions = new ArrayCollection();
    $this->merits = new ArrayCollection();
    $this->discountMerits = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->name;
  }

  /**
   * @return Collection<int, Discipline>
   */
  public function getDisciplines(): Collection
  {
    return $this->disciplines;
  }

  public function addDiscipline(Discipline $discipline): static
  {
    if (!$this->disciplines->contains($discipline)) {
      $this->disciplines->add($discipline);
    }

    return $this;
  }

  public function removeDiscipline(Discipline $discipline): static
  {
    $this->disciplines->removeElement($discipline);

    return $this;
  }

  /**
   * @return Collection<int, Devotion>
   */
  public function getDevotions(): Collection
  {
    return $this->devotions;
  }

  public function addDevotion(Devotion $devotion): static
  {
    if (!$this->devotions->contains($devotion)) {
      $this->devotions->add($devotion);
    }

    return $this;
  }

  public function removeDevotion(Devotion $devotion): static
  {
    $this->devotions->removeElement($devotion);

    return $this;
  }

  /**
   * @return Collection<int, Merit>
   */
  public function getMerits(): Collection
  {
    return $this->merits;
  }

  public function addMerit(Merit $merit): static
  {
    if (!$this->merits->contains($merit)) {
      $this->merits->add($merit);
    }

    return $this;
  }

  public function removeMerit(Merit $merit): static
  {
    $this->merits->removeElement($merit);

    return $this;
  }

  /**
   * @return Collection<int, Merit>
   */
  public function getDiscountMerits(): Collection
  {
      return $this->discountMerits;
  }

  public function addDiscountMerit(Merit $discountMerit): static
  {
      if (!$this->discountMerits->contains($discountMerit)) {
          $this->discountMerits->add($discountMerit);
      }

      return $this;
  }

  public function removeDiscountMerit(Merit $discountMerit): static
  {
      $this->discountMerits->removeElement($discountMerit);

      return $this;
  }
}
