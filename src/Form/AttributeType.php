<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Attribute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class AttributeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var Attribute $attribute */
    $attribute = $options['data'];

    $builder
      // ->add('identifier')
      // ->add('category')
      // ->add('type')
      ->add('name')
      ->add('description', CKEditorType::class, [
        'empty_data' => '', 
        'data' => $converter->convert($attribute->getDescription())
      ])
      ->add('fluff', CKEditorType::class, ['data' => $converter->convert($attribute->getFluff())]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Attribute::class,
    ]);
  }
}
