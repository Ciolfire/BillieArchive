<?php

namespace App\Form;

use App\Entity\CharacterNote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;


class CharacterNoteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var CharacterNote $note */
    $note = $options['data'];
    $converter = new LeagueMarkdown();

    $builder
    ->add('assignedAt', DateType::class, array(
      'widget' => 'single_text',
      'input' => 'datetime_immutable',
      'data' => new \DateTimeImmutable($options['date']),
      'label' => false,
    ))
    ->add('title')
    ->add('content', CKEditorType::class, ['data' => $converter->convert($note->getContent())])
    ->add('save', SubmitType::class);
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => CharacterNote::class,
      'date' => '01-02-2005',
    ]);
  }
}
