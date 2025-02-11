<?php

namespace App\Form;

use App\Entity\Arcanum;
use App\Entity\Book;
use App\Entity\Path;
use App\Form\Type\RichTextEditorType;
use App\Form\Type\SourceableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArcanumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => "name", 'translation_domain' => "app"])
            ->add('purview', RichTextEditorType::class, ['label' => "purview", 'empty_data' => ""])
            ->add('realm', RichTextEditorType::class, ['label' => "realm", 'empty_data' => ""])
            ->add('short', null, ['label' => "short", 'translation_domain' => "app"])
            ->add('source', SourceableType::class, [
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
