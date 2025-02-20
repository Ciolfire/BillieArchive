<?php

declare(strict_types=1);

namespace App\Controller\Mage;

use App\Entity\Path;
use App\Entity\Description;
use App\Form\Mage\PathType;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/mage')]
class PathController extends AbstractController
{
  private DataService $dataService;
  private MageService $service;

  public function __construct(DataService $dataService, MageService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/paths', name: 'mage_path_index', methods: ['GET'])]
  public function paths(): Response
  {
    return $this->render('mage/path/index.html.twig', [
      'paths' => $this->dataService->findAll(Path::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'path']),
    ]);
  }

  #[Route("/wiki/paths/list/{filter<\w+>}/{id<\w+>}", name: "path_list", methods: ["GET"])]
  public function pathList(string $filter, int $id): Response
  {
    $paths = $this->dataService->getList($filter, $id, Path::class, 'getPaths');

    return $this->render('mage/path/index.html.twig', [
      'setting' => "mage",
      'paths' => $paths,
      'filter' => $filter,
      'id' => $id,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'path']),
    ]);
  }


  #[Route('/wiki/path/{id<\d+>}', name: 'mage_path_show', methods: ['GET'])]
  public function pathShow(Path $path): Response
  {
    return $this->render('mage/path/show.html.twig', [
      'path' => $path,
    ]);
  }

  #[Route('/path/new', name: 'mage_path_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $path = new Path($this->dataService->getItem($request->get('filter'), $request->get('id')));
    $form = $this->createForm(PathType::class, $path);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $path->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      $this->dataService->save($path);

      return $this->redirectToRoute('mage_path_index', ['_fragment' => $path->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/path/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/path/{id<\d+>}/edit', name: 'mage_path_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Path $path): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(PathType::class, $path);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $path->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      $this->dataService->update($path);

      return $this->redirectToRoute('mage_path_index', ['_fragment' => $path->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/path/form.html.twig', [
      'form' => $form,
    ]);
  }
}
