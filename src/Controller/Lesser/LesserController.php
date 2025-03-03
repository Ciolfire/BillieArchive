<?php

declare(strict_types=1);

namespace App\Controller\Lesser;

use App\Entity\BloodBather;
use App\Entity\BloodBathFacet;
use App\Entity\ContentType;
use App\Entity\Description;
use App\Entity\Merit;
use App\Form\Lesser\BloodBathType;
use App\Service\CharacterService;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/lesser')]
class LesserController extends AbstractController
{
  private DataService $dataService;
  private CharacterService $service;

  public function __construct(DataService $dataService, CharacterService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/blood_bather', name: 'wiki_blood_bather', methods: ['GET'])]
  public function bloodbather(): Response
  {
    $facets = $this->dataService->findBy(BloodBathFacet::class);

    return $this->render('wiki/lesser/blood_bather.html.twig', [
      'facets' => $facets,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'bloodbather']),
    ]);
  }

  #[Route('/bloodbather/{id<\d+>}/bath/setup', name: 'bloodbather_bath_setup', methods: ['GET', 'POST'])]
  public function bloodbatherBathSetup(Request $request, BloodBather $bather): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(BloodBathType::class, $bather->getBath());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($bather);

      return $this->redirectToRoute('character_show', ['id' => $bather->getSourceCharacter()->getId(), '_fragment' => 'blood_bather']);
    }

    return $this->render('character_sheet_type/blood_bather/bath/edit.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/psychic', name: 'wiki_psychic', methods: ['GET'])]
  public function psychic(): Response
  {
    $type = $this->dataService->findBy(ContentType::class, ['name' => 'psychic']);
    $powers = $this->dataService->findBy(Merit::class, ['type' => $type]);
    /** @var Merit $merit */
    foreach ($powers as $power) {
      $this->dataService->loadPrerequisites($power);
    }

    return $this->render('wiki/lesser/psychic.html.twig', [
      'powers' => $powers,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'psychic']),
    ]);
  }
}
