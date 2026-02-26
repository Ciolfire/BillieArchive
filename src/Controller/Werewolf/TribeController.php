<?php

namespace App\Controller\Werewolf;

use App\Entity\Description;
use App\Entity\Tribe;
use App\Form\Werewolf\TribeForm;
use App\Service\DataService;
use App\Service\WerewolfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/werewolf')]
final class TribeController extends AbstractController
{
  private DataService $dataService;
  private WerewolfService $service;

  public function __construct(DataService $dataService, WerewolfService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/tribes', name: 'werewolf_tribe_index', methods: ['GET'])]
  public function tribes(): Response
  {
    return $this->render('werewolf/tribe/list.html.twig', [
      'tribes' => $this->dataService->findBy(Tribe::class, [
        'homebrewFor' => null,
      ]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'tribe']),
    ]);
  }

  #[Route("/wiki/tribes/list/{filter<\w+>}/{id<\w+>}", name: "werewolf_tribe_list", methods: ["GET"])]
  public function pathList(string $filter, int $id): Response
  {
    $tribes = $this->dataService->getList($filter, $id, Tribe::class, 'getPaths');

    return $this->render('werewolf/tribe/list.html.twig', [
      'tribes' => $tribes,
      'filter' => $filter,
      'id' => $id,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'path']),
    ]);
  }

  #[Route('/wiki/tribe/{id<\d+>}', name: 'werewolf_tribe_show', methods: ['GET'])]
  public function gift(Request $request, Tribe $tribe): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/tribe/$template.html.twig", [
      'tribe' => $tribe,
    ]);
  }

  #[Route('/tribe/new', name: 'werewolf_tribe_new', methods: ['GET', 'POST'])]
  public function tribeNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $tribe = new Tribe();
    $form = $this->createForm(TribeForm::class, $tribe);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $filePath = $this->getParameter('tribes_emblems_directory');
      
      $emblem = $form->get('emblem')->getData();
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $tribe->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      
      $this->dataService->save($tribe);

      $this->addFlash('success', ["general.new.done", ['%name%' => $tribe->getName()]]);

      return $this->redirectToRoute('werewolf_tribe_index', ['_fragment' => $tribe->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/tribe/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/tribe/{id<\d+>}/edit', name: 'werewolf_tribe_edit', methods: ['GET', 'POST'])]
  public function tribeEdit(Request $request, Tribe $tribe): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(TribeForm::class, $tribe);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($tribe);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $tribe->getName()]]);
      return $this->redirectToRoute('werewolf_tribe_index');
    }

    return $this->render('werewolf/tribe/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }
}
