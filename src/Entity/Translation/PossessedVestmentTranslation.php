<?php declare(strict_types=1);
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "possessed_vestment_translation")]
#[ORM\Index(name: "possessed_vestment_translation_idx", columns: ["locale", "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class PossessedVestmentTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
