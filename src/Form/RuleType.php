<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Rule;
use App\Entity\Types\SettingType;
use App\Form\Type\ContentTypeType;
use App\Form\Type\SourceableType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RichTextEditorType;
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
    
    $translator = $this->translator;

    /** @var Rule */
    $element = $options['data'];

    $builder
      ->add('title', null, ['label' => 'title'])
      ->add('details', RichTextEditorType::class, ['empty_data' => '', 'data' => $element->getDetails(), 'label' => false])
      ->add('type', ContentTypeType::class, [
        'data_class' => Rule::class,
        'label' => false,
      ])
      ->add('parentRule', null,
      [
        'label' => 'parent',
        'translation_domain' => 'rule',
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
