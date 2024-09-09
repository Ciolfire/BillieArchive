<?php

namespace App\Form\Type;

use App\Entity\ContentType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\QueryBuilder;

class ContentTypeType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('type', EntityType::class, [
        'label' => "label.single",
        'class' => ContentType::class,
        'choice_label' => function ($choice): string {
          return $choice->getName();
        },
        'choice_translation_domain' => 'content-type',
        'query_builder' => function (EntityRepository $er): QueryBuilder {
          return $er->createQueryBuilder('ct')
            ->orderBy('ct.name', 'ASC');
        },
        'required' => false,
        'empty_data' => null,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'inherit_data' => true,
      'translation_domain' => 'content-type',
    ]);
  }
}
