<?php declare(strict_types=1);
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;


#[ORM\Table(name: "bodythief_society")]
#[ORM\Index(name: "bodythief_society_idx", columns: ["locale", "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class BodyThiefSocietyTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
