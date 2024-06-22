<?php

namespace App\Form;

use App\Entity\Character;
use App\Entity\CharacterInfo;
use App\Entity\Chronicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Contracts\Translation\TranslatorInterface;

class CharacterInfoType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    ;
    $characters = [];
    $character = $options['character'];
    $path = $options['path'];
    if ($character instanceof Character && $character->getChronicle() instanceof Chronicle) {
      $characters = $character->getChronicle()->getPlayerCharacters();
      $id = array_search($character, $characters);
      unset($characters[$id]);
    }
    $builder
      ->add('character', EntityType::class, [
        'class' => Character::class,
        'data' => $character,
        'label' => false,
        'row_attr' => ["class" => "d-none"],
      ])
      ->add('title', null, [
        'label' => 'infos.details.title',
      ])
      ->add('data', TextareaType::class, [
        'label' => 'infos.details.data',
        'attr' => ['style' => "height: 200px"]
      ])
      ->add('accessList', EntityType::class, [
        'label' => 'infos.details.accessList',
        'class' => Character::class,
        'choices' => $characters,
        'choice_label' => function ($choice, string $key, mixed $value) use ($path): string {
          return '<div class="d-inline-block me-1" style="width:40px;">'."<img height=\"40\" src=\"{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"></div>".$choice->getName();
        },
        'label_html' => true,
        'expanded' => true,
        'multiple' => true,
        // 'attr' => ['class' => 'form-control d-flex flex-wrap'],
      ])
      ->add('remove', ButtonType::class, [
        'label' => 'action.delete',
        'translation_domain' => 'app',
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
          'class' => 'btn-warning btn-sm',
        ],
        'row_attr' => [
          'class' => 'border-bottom pb-3 mb-3',
        ],
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterInfo::class,
      'character' => null,
      'translation_domain' => 'character',
      'path' => null,
      'attr' => [
        // 'data-controller' => 'prerequisite',
        'data-form-collection-target' => 'block',
      ],
    ]);
  }
}