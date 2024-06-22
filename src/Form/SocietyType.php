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
          'expanded' => true,
          'label' => false,
          // 'attr' => ['class' => 'form-control'],
          'query_builder' => function (EntityRepository $er) use ($society) {
            return $er->createQueryBuilder('c')->where('c.chronicle = ?1')->orderBy('c.firstName', 'ASC')->setParameter('1', $society->getChronicle()->getId());
          },
          'choice_label' => function ($choice, string $key, mixed $value) use ($path): string {
            return '<div class="d-inline-block me-1" style="width:40px;">'."<img height=\"40\" src=\"{$path}/{$choice->getAvatar()}\"/ onerror=\"this.src='{$path}/default.jpg';this.onerror=null;\"></div>".$choice->getName();
          },
          'label_html' => true,
        ])
      ;
    } else {
      $builder
        ->add('name', null, ['label' => 'name'])
        ->add('description', null, ['label' => 'description.label'])
        // ->add('characters', null, ['expanded' => true])
        ->add('setting', ChoiceType::class, [
          'label' => 'setting',
          'required' => false,
          'choices' => get_class_vars(SettingType::class),
          'choice_label' => function ($choice, $key, $value) {
            return "type.{$key}";
          }
        ])
        ->add('type', ChoiceType::class, [
          'label' => 'type.label',
          'required' => false,
          'choices' => get_class_vars(SocietyCategory::class),
          'choice_label' => function ($choice, $key, $value) {
            return "society.type.{$key}";
          }
        ])
      ;
    }

  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Society::class,
      'translation_domain' => 'app',
      'path' => null,
      'add_character' => false,
    ]);
  }
}
