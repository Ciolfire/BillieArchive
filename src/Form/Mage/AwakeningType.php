<?php declare(strict_types=1);

namespace App\Form\Mage;

use App\Entity\MageOrder;
use App\Entity\Path;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Type\RadiobuttonType;

class AwakeningType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
    ->add('path', RadiobuttonType::class, [
      'choices' => $options['paths'],
      'choice_label' => 'name',
      'choice_attr' => function(Path $path) {
        $rulingArcana = "";
        $inferiorArcanum = $path->getInferiorArcanum()->getId();
        foreach ($path->getRulingArcana()->toArray() as $arcanum) {
          if ($rulingArcana != "") {
            $rulingArcana = "{$rulingArcana} {$arcanum->getId()}";
          } else {
            $rulingArcana = "{$arcanum->getId()}";
          }
        }

        return [
          'data-bs-toggle' => 'tooltip',
          'title' => $path->getTitle(),
          'data-path' => $path->getName(),
          'data-ruling-arcana' => $rulingArcana,
          'data-inferior-arcanum' => $inferiorArcanum,
          'data-action' => 'click->character--awakening#pathPicked',
          'data-character--awakening-target' => 'path'
        ];
      }
    ])
    ->add('order', RadiobuttonType::class, [
      'empty_data' => null,
      'required' => false,
      'placeholder' => null,
      'choices' => $options['orders'],
      'choice_label' => 'name',
      'choice_attr' => function(MageOrder $order) {
        return [
          'data-order' => "mage-order-{$order->getId()}",
          'data-action' => 'click->character--awakening#orderPicked',
          'data-character--awakening-target' => 'order'
        ];
      }
    ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'paths' => null,
      'orders' => null,
      'translation_domain' => false,
      "allow_extra_fields" => true,
    ]);
  }
}
