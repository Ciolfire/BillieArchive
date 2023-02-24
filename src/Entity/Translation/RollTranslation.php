<?php
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;


#[ORM\Table(name: "rolls_translations")]
#[ORM\Index(name: "rolls_translation_idx", columns: ["locale", 'object_class', "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class RollTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
