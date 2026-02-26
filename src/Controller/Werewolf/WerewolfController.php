<?php

declare(strict_types=1);

namespace App\Controller\Werewolf;

use App\Entity\Auspice;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Description;
use App\Entity\GiftList;
use App\Entity\User;
use App\Entity\Werewolf;
use App\Entity\Tribe;
use App\Form\AuspiceForm;
use App\Form\CharacterForm;
use App\Form\Werewolf\FirstChangeForm;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\WerewolfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/werewolf')]
final class WerewolfController extends AbstractController
{
  private DataService $dataService;
  private WerewolfService $service;
  private CharacterService $characterService;
  private CreationService $creationService;

  public function __construct(DataService $dataService, WerewolfService $service, CharacterService $characterService, CreationService $creationService)
  {
    $this->dataService = $dataService;
    $this->service = $service;
    $this->characterService = $characterService;
    $this->creationService = $creationService;
  }

  #[Route('/new/{isAncient<\d+>?0}/{isNpc<\d+>?0}/{chronicle<\d+>?0}', name: 'character_new_werewolf', methods: ['GET', 'POST'])]
  public function newWerewolf(Request $request, bool $isAncient, bool $isNpc, ?Chronicle $chronicle = null): Response
  {
    if (!$isAncient && $chronicle && $chronicle->isAncient()) {
      $isAncient = true;
    }
    $character = new Werewolf(isAncient: $isAncient, isNpc: $isNpc, chronicle: $chronicle);

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

  #[Route('/{id<\d+>}/first_change', name: 'character_first_change', methods: ['GET', 'POST'])]
  public function firstChange(Request $request, Character $character): Response
  {
    if ($character->getType() == "werewolf") {
      $this->addFlash('notice', "Character is already a werewolf");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $auspices = $this->dataService->findAll(Auspice::class);
    $tribes = $this->dataService->findAll(Tribe::class);
    $gifts = $this->dataService->findBy(GiftList::class, [], ['name' => 'ASC']);
    $form = $this->createForm(FirstChangeForm::class, null, ['auspices' => $auspices, 'tribes' => $tribes]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->service->firstChange($character, $form)) {
        if ($character instanceof Werewolf) {
          $this->addFlash('success', ["path.join", ['%name%' => $character->getName(), '%path%' => $character->getAuspice()->getName()]]);
        }
        return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
      }
      $this->addFlash('notice', "Couldn't set the character auspice");
    }

    return $this->render('character_sheet_type/werewolf/first_change/sheet.html.twig', [
      'auspices' => $auspices,
      'tribes' => $tribes,
      'gifts' => $gifts,
      'character' => $character,
      'form' => $form,
    ]);
  }
}
