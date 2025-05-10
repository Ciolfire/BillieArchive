<?php

namespace App\Form\Mage;

use App\Entity\Arcanum;
use App\Form\Type\RichTextEditorForm;
use App\Form\Type\SourceableForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArcanumForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
            ->add('purview', RichTextEditorForm::class, ['label' => "purview", 'empty_data' => ""])
            ->add('realm', RichTextEditorForm::class, ['label' => "realm", 'empty_data' => ""])
            ->add('short', null, ['label' => "short", 'translation_domain' => "app"])
            ->add('source', SourceableForm::class, [
              'data_class' => Arcanum::class,
              'label' => 'source.label',
              'isHomebrewable' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Arcanum::class,
            'translation_domain' => "arcanum",
        ]);
    }
}
