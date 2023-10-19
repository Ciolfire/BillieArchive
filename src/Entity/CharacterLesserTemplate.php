<?php

namespace App\Entity;

use App\Repository\CharacterLesserTemplateRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[ORM\Entity(repositoryClass: CharacterLesserTemplateRepository::class)]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'name', type: 'string')]
#[DiscriminatorMap(['ghoul' => Ghoul::class])]
class CharacterLesserTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'lesserTemplate', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $sourceCharacter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType() {}

    public function getSetting() {}

    public function getSourceCharacter(): ?Character
    {
        return $this->sourceCharacter;
    }

    public function setSourceCharacter(Character $sourceCharacter): static
    {
        $this->sourceCharacter = $sourceCharacter;

        return $this;
    }
}
