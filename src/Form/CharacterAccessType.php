<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterAccess;
use App\Entity\Chronicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterAccessType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $choices = [
      'firstname',
      'lastname',
      'nickname',
      'avatar',
      'age',
      'virtue',
      'vice',
      'description',
      'background',
      'faction',
      'group',
      // 'best.skill.mental',
      // 'best.skill.physical',
      // 'best.skill.social',
    ];
    $characters = [];
    $path = $options['path'];
    $data = $options['data'];
    if ($data instanceof CharacterAccess) {
      $character = $data->getTarget();
      if ($character instanceof Character && $character->getChronicle() instanceof Chronicle) {
        $characters = $character->getChronicle()->getPlayerCharacters();
        unset($characters[array_search($character, $characters)]);
        foreach ($characters as $check) {
          if (!is_null($check->getSpecificPeekingRights($character))) {
            unset($characters[array_search($check, $characters)]);
          }
        }
      }
      $builder
        ->add('rights', ChoiceType::class, [
          'choices' => $choices,
          'choice_label' => function ($choice, string $key, mixed $value) {
            return "right.{$choice}";
          },
          'multiple' => true,
          'expanded' => true,
        ]);
      if (is_null($data->getAccessor())) {
        $builder->add('accessor', EntityType::class, [
          'label' => 'infos.details.accessList',
          'class' => Character::class,
          'choices' => $characters,
          'choice_label' => function ($choice, string $key, mixed $value) use ($path): string {
            return '<div class="d-inline-block me-1" style="width:40px;">'."<img height=\"40\" src=\"{$path}/{$choice->getId()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"></div>".$choice->getName();
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
