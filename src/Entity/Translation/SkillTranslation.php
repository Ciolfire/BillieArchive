<?php declare(strict_types=1);
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;


#[ORM\Table(name: "skills_translations")]
#[ORM\Index(name: "skills_translation_idx", columns: ["locale", 'object_class', "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class SkillTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
