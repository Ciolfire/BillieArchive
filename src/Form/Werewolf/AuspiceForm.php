<?php

namespace App\Form\Werewolf;

use App\Entity\Auspice;
use App\Entity\GiftList;
use App\Entity\Renown;
use App\Entity\Skill;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;

class AuspiceForm extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $translator = $this->translator;
   /** @var Auspice $auspice */
    $auspice = $options['data'];

    $builder
      ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
      ->add('extendedName',  null, ['label' => "name.extended"])
      ->add('short', null, ['label' => "short", 'translation_domain' => "app",])
      ->add('source', SourceableForm::class, [
        'data_class' => Auspice::class,
        'label' => 'source.label',
        'translation_domain' => "book"
      ])
      ->add('emblem', DropzoneType::class, [
        'label' => 'emblem',
        'mapped' => false,
        'required' => false,
        'translation_domain' => 'app',
        'attr' => ['placeholder' => 'upload'],
        'constraints' => [
          new File(
            mimeTypes: [
              'image/*',
            ],
            mimeTypesMessage: 'image.invalid',
          )
        ],
      ])
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $auspice->getDescription(),
        'label' => "description",
        'translation_domain' => 'app',
      ])
      ->add('abilityName', null, ['label' => "ability.name"])
      ->add('ability', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $auspice->getAbility(),
        'label' => "ability.label",
      ])
      ->add('theChange', RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $auspice->getTheChange(),
        'label' => "change.first",
      ])
      ->add('quote', null, ['label' => "quote", 'translation_domain' => "app"])
      ->add('specialtySkills', null, [
        'label' => 'specialty.label',
        'expanded' => true,
        'translation_domain' => "auspice",
        'choice_attr' => ['class' => 'text-sub'],
        'group_by' => function ($choice) use ($translator) {
          /** @var Skill $choice */
          return $translator->trans("category.{$choice->getCategory()}", [], 'app');
        },
      ])
      ->add('renown', EntityType::class, [
        'class' => Renown::class,
        'label' => 'label.single',
        'translation_domain' => "renown",
        'choice_label' => 'name',
      ])
      ->add('phaseGift', EntityType::class, [
        'class' => GiftList::class,
        'label' => 'phase.gift',
        'choice_label' => 'name',
      ])
      ->add('gifts', EntityType::class, [
        'class' => GiftList::class,
        'label' => 'label.multi',
        'translation_domain' => "gift",
        'choice_label' => 'name',
        'expanded' => true,
        'multiple' => true,
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Auspice::class,
      'translation_domain' => "auspice",
    ]);
  }
}
