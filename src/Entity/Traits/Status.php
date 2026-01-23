<?php declare(strict_types=1);

namespace App\Entity\Traits;

use App\Entity\Item;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\StatusEffect;
use Doctrine\Common\Collections\Collection;

trait Status {
  /**
   * @var Collection<int, StatusEffect>
   */
  #[ORM\OneToMany(targetEntity: StatusEffect::class, mappedBy: 'item', cascade: ["persist", "remove"])]
  private Collection $statusEffects;

  /**
   * @return Collection<int, StatusEffect>
   */
  public function getStatusEffects(): array
  {
    $effects = $this->statusEffects->toArray();
    foreach ($effects as $key => $effect) {
      /** @var StatusEffect $effect */
      if ($effect->getOwner()) {
        unset($effects[$key]);
      }
    }

    return ($effects);
  }

  public function addStatusEffect(StatusEffect $statusEffect): static
  {
    if (!$this->statusEffects->contains($statusEffect)) {
      $this->statusEffects->add($statusEffect);
      switch (get_class($this)) {
        case Item::class:
        case is_subclass_of($this, Item::class):
          $statusEffect->setItem($this);
          break;
      }
    }

    return $this;
  }

  public function removeStatusEffect(StatusEffect $statusEffect): static
  {
    if ($this->statusEffects->removeElement($statusEffect)) {
      // set the owning side to null (unless already changed)
      switch (get_class($this)) {
        case Item::class:
        case is_subclass_of($this, Item::class):
          if ($statusEffect->getItem() === $this) {
            $statusEffect->setItem(null);
          }
          break;
        
        default:
          # code...
          break;
      }
    }

    return $this;
  }
}