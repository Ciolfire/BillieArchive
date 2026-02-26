<?php

declare(strict_types=1);

namespace App\Controller\Werewolf;

use App\Entity\Renown;
use App\Entity\Description;
use App\Form\Werewolf\RenownForm;
use App\Service\DataService;
use App\Service\WerewolfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/werewolf')]
final class RenownController extends AbstractController
{
  private DataService $dataService;
  private WerewolfService $service;

  public function __construct(DataService $dataService, WerewolfService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/renowns', name: 'werewolf_renown_index', methods: ['GET'])]
  public function renowns(): Response
  {
    return $this->render('werewolf/renown/list.html.twig', [
      'renowns' => $this->dataService->findAll(Renown::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'renown']),

    ]);
  }

  #[Route('/wiki/renown/{id<\d+>}', name: 'werewolf_renown_show', methods: ['GET'])]
  public function gift(Request $request, Renown $renown): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/renown/$template.html.twig", [
      'renown' => $renown,
    ]);
  }

  #[Route('/renown/new', name: 'werewolf_renown_new', methods: ['GET', 'POST'])]
  public function renownNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $renown = new Renown();
    $form = $this->createForm(RenownForm::class, $renown);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($renown);

      $this->addFlash('success', ["general.new.done", ['%name%' => $renown->getName()]]);

      return $this->redirectToRoute('werewolf_renown_index', ['_fragment' => $renown->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/renown/{id<\d+>}/edit', name: 'werewolf_renown_edit', methods: ['GET', 'POST'])]
  public function renownEdit(Request $request, Renown $renown): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(RenownForm::class, $renown);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($renown);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $renown->getName()]]);
      return $this->redirectToRoute('werewolf_renown_index');
    }

    return $this->render('werewolf/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }
}
