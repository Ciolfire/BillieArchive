<?php

declare(strict_types=1);

namespace App\Controller\Werewolf;

use App\Entity\Auspice;
use App\Entity\Description;
use App\Form\Werewolf\AuspiceForm;
use App\Service\DataService;
use App\Service\WerewolfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/werewolf')]
final class AuspiceController extends AbstractController
{
  private DataService $dataService;
  private WerewolfService $service;

  public function __construct(DataService $dataService, WerewolfService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/auspices', name: 'werewolf_auspice_index', methods: ['GET'])]
  public function auspices(): Response
  {
    return $this->render('werewolf/auspice/list.html.twig', [
      'auspices' => $this->dataService->findBy(Auspice::class, [
        'homebrewFor' => null,
      ]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'auspice']),

    ]);
  }

  #[Route("/wiki/auspices/list/{filter<\w+>}/{id<\w+>}", name: "werewolf_auspice_list", methods: ["GET"])]
  public function pathList(string $filter, int $id): Response
  {
    $auspices = $this->dataService->getList($filter, $id, Auspice::class, 'getPaths');

    return $this->render('werewolf/auspice/list.html.twig', [
      'auspices' => $auspices,
      'filter' => $filter,
      'id' => $id,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'path']),
    ]);
  }

  #[Route('/wiki/auspice/{id<\d+>}', name: 'werewolf_auspice_show', methods: ['GET'])]
  public function gift(Request $request, Auspice $auspice): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/auspice/$template.html.twig", [
      'auspice' => $auspice,
    ]);
  }

  #[Route('/auspice/new', name: 'werewolf_auspice_new', methods: ['GET', 'POST'])]
  public function auspiceNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $auspice = new Auspice();
    $form = $this->createForm(AuspiceForm::class, $auspice);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $filePath = $this->getParameter('auspices_emblems_directory');
      
      $emblem = $form->get('emblem')->getData();
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $auspice->setEmblem($this->dataService->upload($emblem, $filePath));
      }

    $this->dataService->save($auspice);

      $this->addFlash('success', ["general.new.done", ['%name%' => $auspice->getName()]]);

      return $this->redirectToRoute('werewolf_auspice_index', ['_fragment' => $auspice->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/auspice/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/auspice/{id<\d+>}/edit', name: 'werewolf_auspice_edit', methods: ['GET', 'POST'])]
  public function auspiceEdit(Request $request, Auspice $auspice): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(AuspiceForm::class, $auspice);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($auspice);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $auspice->getName()]]);
      return $this->redirectToRoute('werewolf_auspice_index');
    }

    return $this->render('werewolf/auspice/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }
}
