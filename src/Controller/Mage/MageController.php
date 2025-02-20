<?php

declare(strict_types=1);

namespace App\Controller\Mage;

use App\Entity\Arcanum;
use App\Entity\Character;
use App\Entity\Mage;
use App\Entity\MageOrder;
use App\Entity\Path;
use App\Form\Mage\AwakeningType;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/mage')]
final class MageController extends AbstractController
{
  private DataService $dataService;
  private MageService $service;

  public function __construct(DataService $dataService, MageService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/{id<\d+>}/awakening', name: 'character_awakening', methods: ['GET', 'POST'])]
  public function awakening(Request $request, Character $character): Response
  {
    if ($character->getType() == "mage") {
      $this->addFlash('notice', "Character is already a mage");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $paths = $this->dataService->findAll(Path::class);
    $orders = $this->dataService->findAll(MageOrder::class);
    $arcana = $this->dataService->findBy(Arcanum::class, [], ['name' => 'ASC']);
    $form = $this->createForm(AwakeningType::class, null, ['paths' => $paths, 'orders' => $orders]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->service->awaken($character, $form)) {
        if ($character instanceof Mage) {
          $this->addFlash('success', ["path.join", ['%name%' => $character->getName(), '%path%' => $character->getPath()->getName()]]);
        }
        return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
      }
      $this->addFlash('notice', "Couldn't set the character clan");
    }

    return $this->render('character_sheet_type/mage/awakening/sheet.html.twig', [
      'character' => $character,
      'paths' => $paths,
      'orders' => $orders,
      'arcana' => $arcana,
      'form' => $form,
    ]);
  }
}
