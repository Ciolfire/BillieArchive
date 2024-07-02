<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Rule;
use App\Entity\Types\SettingType;
use App\Form\Type\SourceableType;
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
  public TranslatorInterface $translator;
  
  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }
  
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $converter = new LeagueMarkdown();
    $translator = $this->translator;

    /** @var Rule */
    $element = $options['data'];

    $builder
      ->add('title', null, ['label' => 'title'])
      ->add('details', CKEditorType::class, ['empty_data' => '', 'data' => $converter->convert($element->getDetails()), 'label' => false])
      ->add('type', ChoiceType::class, [
        'label' => 'type.label',
        'required' => false,
        'choices' => get_class_vars(SettingType::class),
        'choice_label' => function ($choice, $key, $value) {
          return "type.{$key}";
        }
      ])
      ->add('parentRule', null,
      [
        'label' => 'rule.parent',
        'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('r')->where('r.parentRule IS NULL')->orderBy('r.title', 'ASC');
        }
      ],)
      ->add('source', SourceableType::class, [
        'data_class' => Rule::class,
        'label' => 'source.label',
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Rule::class,
      'translation_domain' => 'app',
    ]);
  }
}
