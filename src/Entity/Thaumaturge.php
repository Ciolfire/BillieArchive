<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\Lesser\ThaumaturgeForm;
use App\Repository\ThaumaturgeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThaumaturgeRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Thaumaturge extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\ManyToOne]
  private ?ThaumaturgeTradition $tradition = null;

  public function __construct() {}

  public function __clone()
  {
    parent::__clone();
  }

  public function getType(): string
  {
    return "thaumaturge";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm(): ?string
  {
    return ThaumaturgeForm::class;
  }

  public function getPowerRating(array $weight): int
  {
    $sum = 0;
    foreach ($this->getSourceCharacter()->getMerits() as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->getType() == "thaumaturge" && is_null($merit->getMerit()->getCategory())) {
        $sum += $weight[$merit->getLevel()] * 4;
      }
    }

    return $sum * 4;
  }

  public function getTradition(): ?ThaumaturgeTradition
  {
    return $this->tradition;
  }

  public function setTradition(?ThaumaturgeTradition $tradition): static
  {
    $this->tradition = $tradition;

    return $this;
  }

  public function getMerits(): ?Array
  {
    $merits = [];

    foreach ($this->getSourceCharacter()->getMerits() as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->getType() == "thaumaturge" && is_null($merit->getMerit()->getCategory())) {
        $key = $merit->getMerit()->getName() . (10 - $merit->getLevel()) . $merit->getId();
        $merits[$key] = $merit;
      }
    }
    ksort($merits);

    return $merits;
  }
}
