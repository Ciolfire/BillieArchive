<?php

namespace App\Form;

use App\Entity\Item;
use App\Form\Type\SourceableType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Twig\Extra\Markdown\LeagueMarkdown;

class ItemType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    /** @var Item $item */
    $item = $options['data'];

    $builder
      ->add('name', null, ['label' => 'name',])
      ->add('source', SourceableType::class, [
        'data_class' => Item::class,
        'label' => 'source.label',
        ])
      ->add('img', DropzoneType::class, [
        'label' => false,
        'attr' => ['placeholder' => 'picture.upload'],
        'mapped' => false,
        'required' => false,
        'help' => 'help.img',
      ])
      ->add('isContainer', null, [
        'label' => 'is.container',
        'help' => 'help.container',
      ]);
      if ($item->getOwner()) {
        $builder->add('container', null, [
          'label' => 'container.label',
          'help' => 'help.container',
          'choices' => $item->getOwner()->getItemContainers(),
        ]);
      }
      $builder->add('durability', TextType::class, [
        'label' => 'durability.label',
        'required' => false,
      ])
      ->add('size', null, ['label' => 'size.label',])
      ->add('cost', ChoiceType::class, [
        'label' => 'cost.label',
        'choices' => array_combine(range(1, 5), range(1, 5)),
        'multiple' => true,
        'expanded' => true,
        ])
      ->add('description', CKEditorType::class, [
        'label' => 'description',
        'empty_data' => '',
        'data' => $converter->convert($item->getDescription()),
        'translation_domain' => 'app',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Item::class,
      'translation_domain' => 'item',
    ]);
  }
}
