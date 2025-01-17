<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\InnocentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InnocentRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
class Innocent extends CharacterLesserTemplate
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
    return "innocents";
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
