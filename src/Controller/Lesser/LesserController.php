<?php

declare(strict_types=1);

namespace App\Controller\Lesser;

use App\Entity\BloodBather;
use App\Entity\BloodBathFacet;
use App\Entity\Character;
use App\Entity\ContentType;
use App\Entity\Description;
use App\Entity\Merit;
use App\Form\Lesser\BloodBathForm;
use App\Form\LesserTemplateForm;
use App\Service\CharacterService;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

  #[Route('/{id<\d+>}/add', name: 'character_lesser_add', methods: ['GET', 'POST'])]
  public function applyLesserTemplate(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    // We get the current lesser template, if any
    $currentTemplate = $character->getLesserTemplate();
    $result = $this->service->lesserTemplatesGetAllAvailable($currentTemplate);
    $form = $this->createForm(LesserTemplateForm::class, options:['templates' => $result['templates']]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      // Fetch the new lesser template class from the form
      $newTemplate = $form->getData()['template'];
      // If the character already has a lesser template
      if (!is_null($currentTemplate)) {
        if ($currentTemplate->getType() == $newTemplate) {
          // Same template, shouldn't happen, return
          $this->addFlash('error', 'character.template.lesser.same', [
            'name' => $character->getName(),
            'type' => $currentTemplate->getType(),
          ]);
          return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
        }
        // Otherwise, we deactivate the old template
        $this->service->lesserTemplateRemove($character, $currentTemplate);
        $this->addFlash('notice', ['character.template.lesser.deactivated', [
          'name' => $character->getName(),
          'old' => $currentTemplate->getType(),
        ]]);
      }
      // Setup/update of the new lesser template
      $this->service->lesserTemplateAdd($character, $newTemplate, $request->request->all());
      $this->addFlash('success', ["character.template.lesser.add", [
        'name' => $character, 
        'type' => $character->getLesserTemplate()->getType(),
      ]]);
      switch ($character->getLesserTemplate()->getType()) {
        case 'blood_bather':
          return $this->redirectToRoute('bloodbather_bath_setup', ['id' => $character->getLesserTemplate()->getId()]);
          case 'possessed':
            return $this->redirectToRoute('possessed_setup', ['id' => $character->getLesserTemplate()->getId()]);
      }
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    return $this->render('character/lesser/add.html.twig', [
      'form' => $form,
      'descriptions' => $result['descriptions'],
      'character' => $character
    ]);
  }

  #[Route('/{id<\d+>}/remove', name: 'character_lesser_remove', methods: ['GET'])]
  public function removeLesserTemplate(Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($character->getLesserTemplate()) {
      $type = $character->getLesserTemplate()->getType();
      $this->service->lesserTemplateRemove($character, $character->getLesserTemplate());
      $this->addFlash('notice', ["character.template.lesser.remove", ['name' => $character, 'type' => $type]]);
    } else {
      $this->addFlash('notice', ["character.template.lesser.error", ['name' => $character]]);
    }

    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
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
    $this->denyAccessUnlessGranted('edit', $bather->getSourceCharacter());

    $form = $this->createForm(BloodBathForm::class, $bather->getBath());
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
