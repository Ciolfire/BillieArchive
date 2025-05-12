<?php

namespace App\Form\Mage;

use App\Entity\Attainment;
use App\Form\Type\RichTextEditorForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttainmentForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('level')
      ->add('description', RichTextEditorForm::class, [
        'empty_data' => "",
      ])
      ->add('remove', ButtonType::class, [
        'attr' => [
          'data-action' => 'form-collection#removeCollectionElement',
          'class' => 'btn-warning w-25',
        ],
        'row_attr' => [
          'class' => 'text-center',
        ],
        'label' => 'action.remove',
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Attainment::class,
      'empty_data' => function (FormInterface $form, $data): ?Attainment {
        return new Attainment($form->getParent()->getParent()->getData());
      },
      'translation_domain' => "app",
      'attr' => [
        'data-form-collection-target' => 'block',
        'class' => "bdr p-2 rounded",
      ],
    ]);
  }
}
