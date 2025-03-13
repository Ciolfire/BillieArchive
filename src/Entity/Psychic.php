<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PsychicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PsychicRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Psychic extends CharacterLesserTemplate
{
  protected $limit = 5;

  public function __construct()
  {
  }

  public function __clone()
  {
    parent::__clone();
  }

  public function getType(): string
  {
    return "psychic";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm() : ?string
  {
    return null;
  }

  public function getPowerRating(array $weight): int
  {
    $sum = 0;
    foreach ($this->getSourceCharacter()->getMerits() as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->getType() == "psychic" && is_null($merit->getMerit()->getCategory())) {
        $sum += $weight[$merit->getLevel()] * 4;
      }
    }

    return $sum * 4;
  }

  public function getPowers()
  {
    $powers = [];

    foreach ($this->getSourceCharacter()->getMerits() as $merit) {
      if ($merit instanceof CharacterMerit && $merit->getMerit()->getType() == "psychic") {
        $key = $merit->getMerit()->getName() . (10 - $merit->getLevel()) . $merit->getId();
        $powers[$key] = $merit;
      }
    }
    ksort($powers);

    return $powers;
  }
}
