<?php

declare(strict_types=1);

namespace App\Controller\Lesser;

use App\Entity\ContentType;
use App\Entity\ThaumaturgeTradition;
use App\Entity\Description;
use App\Entity\Merit;
use App\Form\Lesser\ThaumaturgeTraditionForm;
use App\Service\CharacterService;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/thaumaturge')]
class ThaumaturgeController extends AbstractController
{
  private DataService $dataService;
  private CharacterService $service;

  public function __construct(DataService $dataService, CharacterService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('', name: 'wiki_thaumaturge', methods: ['GET'])]
  public function wiki(): Response
  {
    $type = $this->dataService->findBy(ContentType::class, ['name' => 'thaumaturge']);
    $powers = $this->dataService->findBy(Merit::class, ['type' => $type], ['name' => 'ASC']);
    $traditions = $this->dataService->findBy(ThaumaturgeTradition::class, [], ['name' => 'ASC']);
    foreach ($powers as $power) {
      $this->dataService->loadPrerequisites($power);
    }

    return $this->render('wiki/lesser/thaumaturge.html.twig', [
      'traditions' => $traditions,
      'powers' => $powers,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'thaumaturge']),
    ]);
  }

  #[Route('/wiki/tradition/{id<\d+>}', name: 'thaumaturge_tradition_show', methods: ['GET'])]
  public function traditionShow(ThaumaturgeTradition $tradition): Response
  {
    return $this->render('human/lesser/thaumaturge/tradition/show.html.twig', [
      'tradition' => $tradition,
    ]);
  }

  #[Route('/tradition/new', name: 'thaumaturge_tradition_new', methods: ['GET', 'POST'])]
  public function traditionNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $tradition = new ThaumaturgeTradition($this->dataService->getItem($request->get('filter'), $request->get('id')));
    $form = $this->createForm(ThaumaturgeTraditionForm::class, $tradition);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // $emblem = $form->get('emblem')->getData();
      // $fileThaumaturgeTradition = $this->getParameter('traditions_emblems_directory');
      // if ($emblem instanceof UploadedFile && is_string($fileThaumaturgeTradition)) {
      //   $tradition->setEmblem($this->dataService->upload($emblem, $fileThaumaturgeTradition));
      // }
      $this->dataService->save($tradition);

      return $this->redirectToRoute('wiki_thaumaturge', ['_fragment' => "tradition-{$tradition->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/tradition/{id<\d+>}/edit', name: 'thaumaturge_tradition_edit', methods: ['GET', 'POST'])]
  public function traditionEdit(Request $request, ThaumaturgeTradition $tradition): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(ThaumaturgeTraditionForm::class, $tradition);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($tradition);

      return $this->redirectToRoute('wiki_thaumaturge', ['_fragment' => "tradition-{$tradition->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('form.html.twig', [
      'form' => $form,
    ]);
  }
}
