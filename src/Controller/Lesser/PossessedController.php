<?php

declare(strict_types=1);

namespace App\Controller\Lesser;

use App\Entity\PossessedVestment;
use App\Entity\Description;
use App\Entity\Possessed;
use App\Entity\Vice;
use App\Form\Lesser\PossessedVestmentForm;
use App\Service\CharacterService;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/possessed')]
class PossessedController extends AbstractController
{
  private DataService $dataService;
  private CharacterService $service;

  public function __construct(DataService $dataService, CharacterService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('', name: 'wiki_possessed', methods: ['GET'])]
  public function possessed(): Response
  {
    return $this->render('wiki/lesser/possessed.html.twig', [
      'vices' => $this->dataService->findAll(Vice::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'possessed']),
    ]);
  }

  #[Route('/setup/{id<\d+>}', name: 'possessed_setup', methods: ['GET', 'POST'])]
  public function possessedSetup(Request $request, Possessed $possessed): Response
  {
    $character = $possessed->getSourceCharacter();
    $this->denyAccessUnlessGranted('edit', $character);
    if (!$possessed->getVices()->isEmpty()) {
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }
    $form = $this->createFormBuilder(null, ['allow_extra_fields' => true])->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->service->applyPossessed($possessed, $form->getExtraData());
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    return $this->render('character_sheet_type/possessed/setup.html.twig', [
      'possessed' => $possessed,
      'vices' => $this->dataService->findAll(Vice::class),
      'form' => $form,
    ]);
  }

  #[Route('/wiki/vestment/{id<\d+>}', name: 'possessed_vestment_show', methods: ['GET'])]
  public function vestmentShow(PossessedVestment $vestment): Response
  {
    return $this->render('human/lesser/possessed/vestment/show.html.twig', [
      'vestment' => $vestment,
    ]);
  }

  #[Route('/vestment/new', name: 'possessed_vestment_new', methods: ['GET', 'POST'])]
  public function vestmentNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $vestment = new PossessedVestment();
    $form = $this->createForm(PossessedVestmentForm::class, $vestment);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($vestment);

      return $this->redirectToRoute('wiki_possessed', ['_fragment' => "vestment-{$vestment->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('human/lesser/possessed/vestment/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/vestment/{id<\d+>}/edit', name: 'possessed_vestment_edit', methods: ['GET', 'POST'])]
  public function vestmentEdit(Request $request, PossessedVestment $vestment): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(PossessedVestmentForm::class, $vestment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($vestment);

      return $this->redirectToRoute('wiki_possessed', ['_fragment' => "vestment-{$vestment->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('human/lesser/possessed/vestment/form.html.twig', [
      'form' => $form,
      'action' => "edit",
    ]);
  }
}
