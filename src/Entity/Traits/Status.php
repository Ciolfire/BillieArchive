<?php declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\StatusEffect;
use Doctrine\Common\Collections\Collection;

trait Status {
  /**
   * @var Collection<int, StatusEffect>
   */
  #[ORM\OneToMany(targetEntity: StatusEffect::class, mappedBy: 'item', cascade: ["persist"])]
  private Collection $statusEffects;

  /**
   * @return Collection<int, StatusEffect>
   */
  public function getStatusEffects(): Collection
  {
    $effects = $this->statusEffects->toArray();
    foreach ($effects as $key => $effect) {
      /** @var StatusEffect $effect */
      if ($effect->getOwner()) {
        unset($effects[$key]);
      }
    }

    return ($effects);
    // dd($effects);
  }

  public function addStatusEffect(StatusEffect $statusEffect): static
  {
    if (!$this->statusEffects->contains($statusEffect)) {
      $this->statusEffects->add($statusEffect);
      $statusEffect->setDisciplinePower($this);
    }

    return $this;
  }

  public function removeStatusEffect(StatusEffect $statusEffect): static
  {
    if ($this->statusEffects->removeElement($statusEffect)) {
      // set the owning side to null (unless already changed)
      if ($statusEffect->getDisciplinePower() === $this) {
        $statusEffect->setDisciplinePower(null);
      }
    }

    return $this;
  }
}