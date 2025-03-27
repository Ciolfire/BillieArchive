<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\PurifiedRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurifiedRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Purified extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $chi = 1;

  #[ORM\Column(type: Types::SMALLINT)]
  private int $essence = 1;

  public function __construct()
  {
  }

  public function __clone()
  {
    parent::__clone();
  }

  public function getType(): string
  {
    return "purified";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm() : ?string
  {
    return null;
  }

  public function getChi(): ?int
  {
    return $this->chi;
  }

  public function setChi(int $chi): self
  {
    $this->chi = $chi;

    return $this;
  }

  public function getMaxEssence(): int
  {
    // if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('purified'))) {

    //   return $this->getChronicle()->getRules('purified')['maxEssence'][$this->chi];
    // }
    switch ($this->chi) {
      case 5:
        return 20;
      case 4:
        return 15;
      case 3:
        return 12;
      case 2:
        return 9;
      default:
        return 6;
    }
  }

  public function getMaxEssencePerTurn(): int
  {
    if (!is_null($this->getChronicle()) && !is_null($this->getChronicle()->getRules('mage'))) {

      return $this->getChronicle()->getRules('mage')['maxEssencePerTurn'][$this->chi];
    }
    switch ($this->chi) {
      case 5:
        return 3;
      case 4:
      case 3:
        return 2;
      default:
        return 1;
    }
  }

  public function getEssence(): ?int
  {
    return $this->essence;
  }

  public function setEssence(int $essence): self
  {
    $this->essence = $essence;

    return $this;
  }
}
