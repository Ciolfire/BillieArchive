<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Note;
use App\Entity\NoteCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteSearchType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('toFind', null, ['label' => false])
      ->add('search', SubmitType::class, ['label' => 'action.search', 'attr' => [
        'class' => 'w-100 btn btn-primary',
      ]]);
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'translation_domain' => 'app',
    ]);
  }
}
