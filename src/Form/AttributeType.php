<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\RichTextEditorType;

class AttributeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    
    /** @var Attribute $attribute */
    $attribute = $options['data'];

    $builder
      // ->add('identifier')
      // ->add('category')
      // ->add('type')
      ->add('name')
      ->add('description', RichTextEditorType::class, [
        'empty_data' => '',
        'data' => $attribute->getDescription()
      ])
      ->add('fluff', RichTextEditorType::class, ['data' => $attribute->getFluff()]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Attribute::class,
    ]);
  }
}
