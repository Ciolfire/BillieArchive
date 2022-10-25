<?php
namespace App\Entity\Translation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

/**
 * @ORM\Table(name="clan_translations", indexes={
 *      @ORM\Index(name="clan_translation_idx", columns={"locale", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 */
class ClanTranslation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}
