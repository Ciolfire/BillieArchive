<?php

namespace App\Entity;

use App\Repository\MeritRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Translation\MeritTranslation;

/**
 * @ORM\Table(name="merits")
 * @ORM\Entity(repositoryClass=MeritRepository::class)
 * @Gedmo\TranslationEntity(class="App\Entity\Translation\MeritTranslation")
 */
class Merit implements Translatable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $category;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFighting;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isExpanded;

    /**
     * @ORM\Column(type="smallint")
     */
    private $min;

    /**
     * @ORM\Column(type="smallint")
     */
    private $max;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUnique;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCreationOnly;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $prerequisites = [];

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     */
    private $effect;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $book;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getIsFighting(): ?bool
    {
        return $this->isFighting;
    }

    public function setIsFighting(bool $isFighting): self
    {
        $this->isFighting = $isFighting;

        return $this;
    }

    public function getIsExpanded(): ?bool
    {
        return $this->isExpanded;
    }

    public function setIsExpanded(bool $isExpanded): self
    {
        $this->isExpanded = $isExpanded;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(int $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getIsUnique(): ?bool
    {
        return $this->isUnique;
    }

    public function setIsUnique(bool $isUnique): self
    {
        $this->isUnique = $isUnique;

        return $this;
    }

    public function getIsCreationOnly(): ?bool
    {
        return $this->isCreationOnly;
    }

    public function setIsCreationOnly(bool $isCreationOnly): self
    {
        $this->isCreationOnly = $isCreationOnly;

        return $this;
    }

    public function getPrerequisites(): ?array
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?array $prerequisites): self
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): self
    {
        $this->effect = $effect;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBook(): ?string
    {
        return $this->book;
    }

    public function setBook(?string $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
