<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\User;
use App\Entity\Clan;
use App\Entity\Covenant;
use App\Entity\Discipline;
use App\Entity\Vampire;
use App\Form\Vampire\EmbraceForm;
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

  #[Route('/{id<\d+>}/embrace', name: 'character_embrace', methods: ['GET', 'POST'])]
  public function embrace(Request $request, Character $character): Response
  {
    if ($character->getType() == "vampire") {
      $this->addFlash('notice', "Character is already a vampire");

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $clans = $this->dataService->findBy(Clan::class, ['isBloodline' => false]);
    $covenants = $this->dataService->findAll(Covenant::class);
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
