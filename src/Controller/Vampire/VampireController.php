<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\User;
use App\Entity\Clan;
use App\Entity\Discipline;
use App\Form\Vampire\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class VampireController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/', name: 'vampire_index', methods: ['GET'])]
  public function vampires(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => false
      ]),
      'npc' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ]),
    ]);
  }

  #[Route('/{id<\d+>}/embrace', name: 'character_embrace', methods: ['GET', 'POST'])]
  public function embrace(Request $request, Character $character): Response
  {
    if ($character->getType() == "vampire") {
      $this->addFlash('notice', "Character is already a vampire");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $clans = $this->dataService->findBy(Clan::class, ['isBloodline' => false]);
    $attributes = $this->dataService->findAll(Attribute::class);
    $disciplines = $this->dataService->findAll(Discipline::class);
    $form = $this->createForm(EmbraceType::class, null, ['clans' => $clans, 'attributes' => $attributes]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($this->service->embrace($character, $form)) {

        return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
      }
      $this->addFlash('notice', "Couldn't set the character clan");
    }
    return $this->render('character_sheet_type/vampire/embrace/sheet.html.twig', [
      'character' => $character,
      'clans' => $clans,
      'disciplines' => $disciplines,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/ghoulify', name: 'character_ghoulify', methods: ['GET', 'POST'])]
  public function ghoulify(Request $request, Character $character): Response
  {
    // $clans = $this->dataService->findBy(Clan::class, ['isBloodline' => false]);
    // $attributes = $this->dataService->findAll(Attribute::class);
    // $disciplines = $this->dataService->findAll(Discipline::class);
    // $form = $this->createForm(EmbraceType::class, null, ['clans' => $clans, 'attributes' => $attributes]);
    // $form->handleRequest($request);

    // if ($form->isSubmitted() && $form->isValid()) {
      // if ($this->)
      $this->service->ghoulify($character);
      $this->addFlash('success', ["character.template.lesser.add", ['name' => $character, 'type' => $character->getLesserTemplate()->getType()]]);
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    // }
    // return $this->render('character_sheet_type/vampire/embrace/sheet.html.twig', [
    //   'character' => $character,
    //   'clans' => $clans,
    //   'disciplines' => $disciplines,
    //   'form' => $form,
    // ]);
  }
}
