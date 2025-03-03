<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Types\BodyThiefTalent;
use App\Form\Lesser\BodyThiefType;
use App\Repository\BodyThiefRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BodyThiefRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class BodyThief extends CharacterLesserTemplate
{
  protected $limit = 5;

  #[ORM\ManyToOne]
  private ?BodyThiefSociety $society = null;

  #[ORM\Column(type: Types::INTEGER, enumType: BodyThiefTalent::class)]
  private BodyThiefTalent $talentType = BodyThiefTalent::mental;

  public function __construct()
  {
  }

  public function __clone()
  {
    parent::__clone();
  }

  public function getType(): string
  {
    return "body_thief";
  }

  public function getSetting(): string
  {
    return "human";
  }

  public static function getForm() : ?string
  {
    return BodyThiefType::class;
  }

  public function getSociety(): ?BodyThiefSociety
  {
    return $this->society;
  }

  public function setSociety(?BodyThiefSociety $society): static
  {
    $this->society = $society;

    return $this;
  }

  public function getPossessionMethod(): BodyThiefTalent
  {
    return $this->talentType;
  }

  public function setPossessionMethod(BodyThiefTalent $talentType): static
  {
    $this->talentType = $talentType;

    return $this;
  }
}
