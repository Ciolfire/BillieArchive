<?php

declare(strict_types=1);

namespace App\Controller\Mage;

use App\Entity\Arcanum;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Mage;
use App\Entity\MageOrder;
use App\Entity\Path;
use App\Form\CharacterForm;
use App\Form\Mage\AwakeningForm;
use App\Service\CharacterService;
use App\Service\CreationService;
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
  private CharacterService $characterService;
  private CreationService $creationService;

  public function __construct(DataService $dataService, MageService $service, CharacterService $characterService, CreationService $creationService)
  {
    $this->dataService = $dataService;
    $this->service = $service;
    $this->characterService = $characterService;
    $this->creationService = $creationService;
  }

  #[Route('/new/{isAncient<\d+>?0}/{isNpc<\d+>?0}/{chronicle<\d+>?0}', name: 'character_new_mage', methods: ['GET', 'POST'])]
  public function newMage(Request $request, bool $isAncient, bool $isNpc, ?Chronicle $chronicle = null): Response
  {
    if (!$isAncient && $chronicle && $chronicle->isAncient()) {
      $isAncient = true;
    }
    $character = new Mage(isAncient: $isAncient, isNpc: $isNpc, chronicle: $chronicle);

    /** @var User $user */
    $user = $this->getUser();
    $character->setPlayer($user);
    $form = $this->createForm(CharacterForm::class, $character, ['user' => $this->getUser()]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->creationService->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->get('resolve', false) + $character->getAttributes()->get('composure', false));

      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $merits = $this->characterService->filterMerits($character);
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->characterService->getSortedAttributes(),
      'skills' => $this->characterService->getskillList($character),
      'merits' => $merits,
    ]);
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
    $form = $this->createForm(AwakeningForm::class, null, ['paths' => $paths, 'orders' => $orders]);
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
