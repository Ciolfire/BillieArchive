<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Discipline;
use App\Entity\Vampire;
use App\Form\Creation\VampireForm;
use App\Form\Vampire\EmbraceForm;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
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
  public function newVampire(Request $request, FormFactoryInterface $formFactory, bool $isAncient, bool $isNpc, ?Chronicle $chronicle = null): Response
  {
    if (!$isAncient && $chronicle && $chronicle->isAncient()) {
      $isAncient = true;
    }
    $vampire = new Vampire(isAncient: $isAncient, isNpc: $isNpc, chronicle: $chronicle);

    $vampire->setPlayer($this->getUser());
    $merits = $this->characterService->filterMerits($vampire);
    $clans = $this->dataService->getDoctrine()->getRepository(Clan::class)->findAllClan(chronicle: $vampire->getChronicle(), isAncient: $isAncient);
    $covenants = $this->dataService->findBy(Covenant::class, ['isAncient' => $vampire->isAncient()]);
    $attributes = $this->dataService->findAll(Attribute::class);
    // We are creating a vampire, so we use the extended Creation/VampireForm
    $form = $formFactory->createNamed('character_form', VampireForm::class, $vampire, [
      'clans' => $clans,
      'covenants' => $covenants,
      'attributes' => $attributes,
      ]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->creationService->addMerits($vampire, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($vampire, $form);
      $vampire->addAttribute($form->get('attribute')->getData()->getIdentifier(), 1);

      // We make sure the willpower is correct
      $vampire->setWillpower($vampire->getAttributes()->get('resolve', false) + $vampire->getAttributes()->get('composure', false));
      // Bonus experience if lowerer Humanity
      $vampire->setXpTotal((7 - $vampire->getMoral()) * 5);
      $this->service->addDisciplines($vampire, $form->getExtraData()['disciplines']);
      $this->dataService->save($vampire);

      return $this->redirectToRoute('character_show', ['id' => $vampire->getId()]);
    }
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $vampire,
      'form' => $form,
      'attributes' => $this->characterService->getSortedAttributes(),
      'skills' => $this->characterService->getskillList($vampire),
      'merits' => $merits,
      'clans' => $clans,
      'covenants' => $covenants,
      'disciplines' => $this->dataService->findAll(Discipline::class),
  ]);
  }

  #[Route('/{id<\d+>}/embrace', name: 'character_embrace', methods: ['GET', 'POST'])]
  public function embrace(Request $request, Character $character): Response
  {
    if ($character->getType() == "vampire") {
      $this->addFlash('notice', "Character is already a vampire");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $clans = $this->dataService->getDoctrine()->getRepository(Clan::class)->findAllClan($character->getChronicle());
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
