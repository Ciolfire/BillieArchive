<?php
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "disciplines_translations")]
#[ORM\Index(name: "disciplines_translation_idx", columns: ["locale", "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class DisciplineTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
