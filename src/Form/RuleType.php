<?php

namespace App\Form;

use App\Entity\Rule;
use App\Entity\Types\SettingType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Twig\Extra\Markdown\LeagueMarkdown;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\Translation\TranslatorInterface;

class RuleType extends AbstractType
{
  public $translator;
  
  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }
  
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $types = new \ReflectionClass(SettingType::class);
    $converter = new LeagueMarkdown();
    $translator = $this->translator;

    /** @var Rule */
    $element = $options['data'];

    $builder
      ->add('title')
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($element->getDetails()), 'label' => false])
      ->add('type', ChoiceType::class, [
        'required' => false,
        'choices' => $types->getConstants(),
      ])
      ->add('parentRule', null, 
      [
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('r')->where('r.parentRule IS NULL')->orderBy('r.title', 'ASC');
        }
      ],)
      ->add('page')
      ->add('book');
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Rule::class,
    ]);
  }
}