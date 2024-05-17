<?php declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\Sourcable;
use App\Entity\Traits\Typed;
use App\Repository\RuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use League\HTMLToMarkdown\HtmlConverter;

#[ORM\Entity(repositoryClass: RuleRepository::class)]
#[ORM\Cache(usage: "NONSTRICT_READ_WRITE", region: "write_rare")]
#[Gedmo\TranslationEntity(class: "App\Entity\Translation\RuleTranslation")]
class Rule implements Translatable
{
  use Typed;
  use Sourcable;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private string $title = "";

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private string $details = "";

  #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subrules')]
  private ?self $parentRule = null;

  #[ORM\OneToMany(mappedBy: 'parentRule', targetEntity: self::class)]
  private Collection $subrules;

  public function __construct()
  {
    $this->subrules = new ArrayCollection();
  }

  public function __toString()
  {
    return $this->title;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setTranslatableLocale(string $locale) : self
  {
    $this->locale = $locale;

    return $this;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): self
  {
    $this->title = $title;

    return $this;
  }

  public function getDetails(): string
  {
    return $this->details;
  }

  public function setDetails(string $details): self
  {
    $converter = new HtmlConverter();
    $this->details = $converter->convert($details);

    return $this;
  }

  public function getParentRule(): ?self
  {
    return $this->parentRule;
  }

  public function setParentRule(?self $parentRule): self
  {
    $this->parentRule = $parentRule;

    return $this;
  }

  /**
   * @return Collection<int, self>
   */
  public function getSubrules(): Collection
  {
    return $this->subrules;
  }

  public function addSubrule(self $subrule): self
  {
    if (!$this->subrules->contains($subrule)) {
      $this->subrules->add($subrule);
      $subrule->setParentRule($this);
    }

    return $this;
  }

  public function removeSubrule(self $subrule): self
  {
    if ($this->subrules->removeElement($subrule)) {
      // set the owning side to null (unless already changed)
      if ($subrule->getParentRule() === $this) {
        $subrule->setParentRule(null);
      }
    }

    return $this;
  }
}
