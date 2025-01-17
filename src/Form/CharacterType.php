<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Character;
use App\Entity\Chronicle;

use App\Form\CharacterSpecialtyType;
use App\Form\Type\SourceableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Character */
    $character = $options['data'];
    $player = $character->getPlayer();
    $chronicle = $character->getChronicle();

    if ($chronicle) {
      $chronicles = [$chronicle];
    } else {
      if ($player) {
        $chronicles = array_merge($player->getChronicles()->toArray(), $player->getStories()->toArray());
      } else {
        $chronicles = [];
      }
    }
    $builder
      ->add('player')
      ->add('firstName', null, [
        'label' => false,
        'empty_data' => "",
        'attr' => [
          'placeholder' => 'name.first',
        ],
      ])
      ->add('nickname', null, [
        'label' => false,
        'required' => false,
        'empty_data' => "",
        'attr' => [
          'placeholder' => 'name.nick',
        ],
      ])
      ->add('lastName', null, [
        'label' => false,
        'required' => false,
        'empty_data' => "",
        'attr' => [
          'placeholder' => 'name.last',
        ],
      ])
      ->add('age', null, [
        'label' => 'age.label',
      ])
      ->add('birthday', null, [
        'label' => 'age.birthday',
        'attr' => ['min' => "0000-01-01"],
      ])
      ->add('lookAge', null, [
        'label' => 'age.looks.label',
      ])
      ->add('virtue', null, ['label' => 'virtue.label.single'])
      ->add('virtueDetail', null, ['required' => false, 'label' => 'virtue.detail', 'empty_data' => ""])
      ->add('vice', null, ['label' => 'vice.label.single'])
      ->add('viceDetail', null, ['required' => false, 'label' => 'vice.detail', 'empty_data' => ""])
      ->add('concept')
      ->add('faction', null, ['label' => 'faction'])
      ->add('groupName', null, ['label' => 'group'])
      ->add('race', HiddenType::class, ['mapped' => false, 'data' => 'mortal'])
      ->add('attributes', CharacterAttributesType::class)
      ->add('skills', CharacterSkillsType::class);
    if (!is_null($character->getLesserTemplate()) && $character->getLesserTemplate()->getForm()) {
      $builder->add('lesserTemplate', $character->getLesserTemplate()->getForm());
    }
    if (!$options['is_edit']) {
      $builder
        ->add('specialty1', CharacterSpecialtyType::class, [
          'required' => false,
          'mapped' => false,
          'label' => false,
        ])
        ->add('specialty2', CharacterSpecialtyType::class, [
          'required' => false,
          'mapped' => false,
          'label' => false,
        ])
        ->add('specialty3', CharacterSpecialtyType::class, [
          'required' => false,
          'mapped' => false,
          'label' => false,
      ]);
    }
    if ($character->isPremade()) {
      $builder->add('source', SourceableType::class,[
        'data_class' => Character::class,
        'label' => 'source.label',
        'isHomebrewable' => false,
      ]);
    } else {
      $builder->add('chronicle', EntityType::class, [
        'class' => Chronicle::class,
        'choices' => $chronicles,
        'required' => false,
      ]);
    }
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      "data_class" => Character::class,
      "translation_domain" => 'character',
      "allow_extra_fields" => true,
      "is_edit" => false,
    ]);
  }
}
