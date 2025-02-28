<?php

namespace App\Form;

use App\Entity\Society;
use App\Entity\Types\SettingType;
use App\Entity\Types\SocietyType as SocietyCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocietyType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    if ($options['add_character']) {
      /** @var Society $society */
      $society = $options['data'];
      $path = $options['path'];
      $builder
        ->add('characters', null, [
          'label' => false,
          'expanded' => true,
          'attr' => ['class' => 'form-control d-flex flex-wrap'],
          'query_builder' => function (EntityRepository $er) use ($society) {
            return $er->createQueryBuilder('c')
              ->andWhere('c.chronicle = :chronicle')
              ->orderBy('c.firstName', 'ASC')
              ->setParameter('chronicle', $society->getChronicle()->getId())
              ;
          },
          'choice_attr' =>  function ($choice) use ($society) {
            if ($society->hasCharacter($choice)) {
              return ['class' => "order-first"];
            }
            return [];
          },
          'choice_label' => function ($choice) use ($path): string {
            return "<div class=\"d-inline-block {$choice->getType()}\">"."<img class=\"form-select-item-avatar me-1\" height=\"40\" src=\"{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"><span class=\"text-strong\">{$choice->getName()}</span></div>";
          },
          'label_attr' => ['class' => "d-flex flex-column justify-content-center text me-2 form-choice-width text-truncate"],
          'label_html' => true,
        ])
      ;
    } else {
      $builder
        ->add('name', null, ['label' => 'name', 'translation_domain' => 'app'])
        ->add('description', null, ['label' => 'description', 'translation_domain' => 'app'])
        // ->add('characters', null, ['expanded' => true])
        ->add('setting', ChoiceType::class, [
          'label' => 'label',
          'translation_domain' => 'setting',
          'required' => false,
          'choices' => get_class_vars(SettingType::class),
          'choice_label' => function ($choice, $key, $value) {
            return "{$key}.label";
          },
          'choice_translation_domain' => 'setting',
        ])
        ->add('type', ChoiceType::class, [
          'label' => 'type.label',
          'required' => false,
          'choices' => get_class_vars(SocietyCategory::class),
          'choice_label' => function ($choice, $key, $value) {
            return "type.{$key}";
          }
        ])
        ->add('organization', null, [
          'label' => 'label.single', 
          'translation_domain' => 'organization',
          'help' => 'society.help',
        ])
      ;
    }

  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Society::class,
      'translation_domain' => 'society',
      'path' => null,
      'add_character' => false,
    ]);
  }
}
