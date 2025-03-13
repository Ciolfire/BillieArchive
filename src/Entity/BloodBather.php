<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BloodBatherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BloodBatherRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class BloodBather extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\OneToOne(cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private ?BloodBath $bath = null;

  public function __construct()
  {
    $this->bath = new BloodBath();
  }

  public function __clone()
  {
    parent::__clone();
    $this->bath = clone $this->bath;
  }

  public function getType(): string
  {
    return "blood_bather";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm(): ?string
  {
    return null;
  }

  public function getPowerRating(array $weight): int
  {
    $sum = 0;
    foreach ($this->bath->getEffects() as $facet) {
      if (is_numeric($value = intval($facet['modifier'])))
      $sum += $value;
    }

    return $sum * 4;
  }

  public function getBath(): ?BloodBath
  {
    return $this->bath;
  }

  public function setBath(BloodBath $bath): static
  {
    $this->bath = $bath;

    return $this;
  }
}
