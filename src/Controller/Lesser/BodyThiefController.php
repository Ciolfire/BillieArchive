<?php

declare(strict_types=1);

namespace App\Controller\Lesser;

use App\Entity\ContentType;
use App\Entity\Description;
use App\Entity\Merit;
use App\Entity\BodyThiefSociety;
use App\Form\Lesser\BodyThiefSocietyForm;
use App\Service\CharacterService;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/body_thief')]
class BodyThiefController extends AbstractController
{
  private DataService $dataService;
  private CharacterService $service;

  public function __construct(DataService $dataService, CharacterService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('', name: 'wiki_body_thief', methods: ['GET'])]
  public function bodythief(): Response
  {
    $type = $this->dataService->findBy(ContentType::class, ['name' => 'body_thief']);
    $merits = $this->dataService->findBy(Merit::class, ['type' => $type], ['name' => 'ASC']);
    $societies = $this->dataService->findBy(BodyThiefSociety::class, [], ['name' => 'ASC']);
    foreach ($merits as $merit) {
      $this->dataService->loadPrerequisites($merit);
    }

    return $this->render('wiki/lesser/body_thief.html.twig', [
      'societies' => $societies,
      'merits' => $merits,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'body_thief']),
    ]);
  }

  #[Route('/wiki/society/{id<\d+>}', name: 'body_thief_society_show', methods: ['GET'])]
  public function societyShow(Request $request, BodyThiefSociety $society): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("human/lesser/body_thief/society/$template.html.twig", [
      'society' => $society,
    ]);
  }

  #[Route('/society/new', name: 'body_thief_society_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $society = new BodyThiefSociety($this->dataService->getItem($request->get('filter'), $request->get('id')));
    $form = $this->createForm(BodyThiefSocietyForm::class, $society);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($society);

      return $this->redirectToRoute('wiki_body_thief', ['_fragment' => "society-{$society->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/society/{id<\d+>}/edit', name: 'body_thief_society_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, bodythiefSociety $society): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(bodythiefSocietyForm::class, $society);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($society);

      return $this->redirectToRoute('wiki_body_thief', ['_fragment' => "society-{$society->getId()}"], Response::HTTP_SEE_OTHER);
    }

    return $this->render('form.html.twig', [
      'form' => $form,
    ]);
  }
}
