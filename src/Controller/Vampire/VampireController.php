<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Discipline;
use App\Entity\Vampire;
use App\Form\CharacterForm;
use App\Form\Vampire\EmbraceForm;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class VampireController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;
  private CharacterService $characterService;
  private CreationService $creationService;

  public function __construct(DataService $dataService, VampireService $service, CharacterService $characterService, CreationService $creationService)
  {
    $this->dataService = $dataService;
    $this->service = $service;
    $this->characterService = $characterService;
    $this->creationService = $creationService;
  }

  #[Route('/vampire/new/{isAncient<\d+>?0}/{isNpc<\d+>?0}/{chronicle<\d+>?0}', name: 'character_new_vampire', methods: ['GET', 'POST'])]
  public function newVampire(Request $request, bool $isAncient, bool $isNpc, ?Chronicle $chronicle = null): Response
  {
    if (!$isAncient && $chronicle && $chronicle->isAncient()) {
      $isAncient = true;
    }
    $character = new Vampire($isAncient);
    $character->setChronicle($chronicle);
    $character->setIsNpc($isNpc);

    /** @var User $user */
    $user = $this->getUser();
    $character->setPlayer($user);
    $merits = $this->characterService->filterMerits($character);
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
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->characterService->getSortedAttributes(),
      'skills' => $this->characterService->getskillList($character),
      'merits' => $merits,
    ]);
  }

  #[Route('/{id<\d+>}/embrace', name: 'character_embrace', methods: ['GET', 'POST'])]
  public function embrace(Request $request, Character $character): Response
  {
    if ($character->getType() == "vampire") {
      $this->addFlash('notice', "Character is already a vampire");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $clans = $this->dataService->findBy(Clan::class, ['isBloodline' => false, 'isAncient' => $character->isAncient()]);
    $covenants = $this->dataService->findBy(Covenant::class, ['isAncient' => $character->isAncient()]);
    $attributes = $this->dataService->findAll(Attribute::class);
    $disciplines = $this->dataService->findAll(Discipline::class);
    $form = $this->createForm(EmbraceForm::class, null, ['clans' => $clans, 'covenants' => $covenants, 'attributes' => $attributes]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->service->embrace($character, $form)) {

        if ($character instanceof Vampire) {
          $this->addFlash('success', ["clan.join", ['%name%' => $character->getName(), '%clan%' => $character->getClan()->getName()]]);
        }
        return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
      }
      $this->addFlash('notice', "Couldn't set the character clan");
    }
    return $this->render('character_sheet_type/vampire/embrace/sheet.html.twig', [
      'character' => $character,
      'clans' => $clans,
      'covenants' => $covenants,
      'disciplines' => $disciplines,
      'form' => $form,
    ]);
  }
}
