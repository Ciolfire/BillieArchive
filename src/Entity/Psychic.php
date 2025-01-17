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
}
