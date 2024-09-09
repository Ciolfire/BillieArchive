<?php

namespace App\Form;

use App\Entity\Organization;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Twig\Extra\Markdown\LeagueMarkdown;

class OrganizationType extends AbstractType
{
  public TranslatorInterface $translator;

  public function __construct(TranslatorInterface $translator)
  {
      $this->translator = $translator;
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    /** @var Organization */
    $organization = $options['data'];
    $converter = new LeagueMarkdown();
    $translator = $this->translator;

    $builder
      ->add('name')
      ->add('emblem', DropzoneType::class, [
        'label' => false,
        'attr' => ['placeholder' => 'emblem.upload'],
        'mapped' => false,
        'required' => false,
        'help' => 'help.img',
      ])
      ->add('description', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'description',
        'empty_data' => '',
        'data' => $converter->convert($organization->getDescription()),
      ])
      ->add('overview', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'overview',
        'empty_data' => '',
        'data' => $converter->convert($organization->getOverview()),
      ])
      ->add('members', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'members',
        'empty_data' => '',
        'data' => $converter->convert($organization->getMembers()),
      ])
      ->add('philosophy', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'philosophy',
        'empty_data' => '',
        'data' => $converter->convert($organization->getPhilosophy()),
      ])
      ->add('observances', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'observances',
        'empty_data' => '',
        'data' => $converter->convert($organization->getObservances()),
      ])
      ->add('titles', CKEditorType::class, [
        'label_attr' => [
          'class' => 'col-sm-12 text-strong text-center',
        ],
        'label' => 'titles',
        'empty_data' => '',
        'data' => $converter->convert($organization->getTitles()),
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Organization::class,
      'translation_domain' => 'organization',
    ]);
  }
}
