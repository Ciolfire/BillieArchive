<?php declare(strict_types=1);
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Table(name: "discipline_power_translation")]
#[ORM\Index(name: "discipline_power_translation_idx", columns: ["locale", "field", "foreign_key"])]
#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class DisciplinePowerTranslation extends AbstractTranslation
{
  /**
   * All required columns are mapped through inherited superclass
   */
}
