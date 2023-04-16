<?php declare(strict_types=1);
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;


#[ORM\Table(name: "derangements_translations")]
#[ORM\Index(name: "derangements_translation_idx", columns: ["locale", 'object_class', "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class DerangementTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
