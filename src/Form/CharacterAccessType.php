<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterAccess;
use App\Entity\Chronicle;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CharacterAccessType extends AbstractType
{
  public TranslatorInterface $translator;
  
  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;

    $choices = [
      'type',
      'firstname',
      'lastname',
      'nickname',
      'avatar',
      'age',
      'virtue',
      'vice',
      'morality',
      'description',
      'background',
      'faction',
      'group',
      'organization',
      // 'best.skill.mental',
      // 'best.skill.physical',
      // 'best.skill.social',
    ];
    $characters = [];
    $path = $options['path'];
    $data = $options['data'];
    $type = $builder->getData()->getTarget()->getType();

    switch ($type) {
      case 'vampire':
        $typeChoices = [
          'clan',
          'covenant',
          'bloodline',
          'sire',
          'embrace',
          'potency',
        ];
        $choices = [
          'base' => $choices,
          'vampire' => $typeChoices
        ];
        break;
      case 'ghoul':
        $typeChoices = [
          'regent',
          'family',
        ];
        $choices = [
          'base' => $choices,
          'ghoul' => $typeChoices
        ];
      case 'mage':
        $typeChoices = [
          'path',
          'order',
          'legacy',
          'gnosis',
        ];
        $choices = [
          'base' => $choices,
          'mage' => $typeChoices
        ];
      default:
        break;
    }
    if ($data instanceof CharacterAccess) {
      $character = $data->getTarget();
      if ($character instanceof Character && $character->getChronicle() instanceof Chronicle) {
        $characters = $character->getChronicle()->getPlayerCharacters();
        if (false !== array_search($character, $characters)) {
          unset($characters[array_search($character, $characters)]);
        }
        foreach ($characters as $check) {
          if (!is_null($check->getSpecificPeekingRights($character))) {
            unset($characters[array_search($check, $characters)]);
          }
        }
      }
      if (empty($characters) && !$data->getAccessor()) {
        throw new Exception("empty choice of characters for access", 847);
      }
      $builder
        ->add('rights', ChoiceType::class, [
          'choices' => $choices,
          'label' => false,
          'choice_label' => function ($choice, string $key, mixed $value) {
            return "right.{$choice}";
          },
          'choice_attr' => function ($choice, string $key, mixed $value) {
            return ['data-character--access-target' => 'right'];
          },
          'multiple' => true,
          'expanded' => true,
        ])
        ->add('importance', ChoiceType::class, [
          'choices' => ['right.importance.high' => 2, 'right.importance.normal' => 1, 'right.importance.low' => 0],
          'label' => "right.importance.label",
          'multiple' => false,
          'expanded' => true,
        ]);
      if (!$data->getAccessor()) {
        $builder->add('accessor', EntityType::class, [
          'label' => 'infos.details.access.list',
          'class' => Character::class,
          'choices' => $characters,
          'choice_label' => function ($choice, string $key, mixed $value) use ($path): string {
            return '<div class="d-inline-block me-1" style="width:40px;">'."<img height=\"40\" src=\"{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"></div>".$choice->getName();
          },
          'label_html' => true,
          'expanded' => true,
        ]);
      }
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterAccess::class,
      'translation_domain' => "character",
      'path' => "",
    ]);
  }
}
