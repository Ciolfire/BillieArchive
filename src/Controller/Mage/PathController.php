<?php

declare(strict_types=1);

namespace App\Controller\Mage;

use App\Entity\Path;
use App\Entity\Description;
use App\Entity\Legacy;
use App\Form\LegacyType;
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

  #[Route('/wiki/paths', name: 'mage_paths', methods: ['GET'])]
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
  public function pathNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $path = new Path($this->dataService->getItem($request->get('filter'), $request->get('id')));
    $form = $this->createForm(PathType::class, $path);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $symbol = $form->get('symbol')->getData();
      $rune = $form->get('rune')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $path->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $path->setSymbol($this->dataService->upload($symbol, $filePath));
      }
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $path->setRune($this->dataService->upload($rune, $filePath));
      }
      $this->dataService->save($path);

      return $this->redirectToRoute('mage_paths', ['_fragment' => $path->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/path/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/path/{id<\d+>}/edit', name: 'mage_path_edit', methods: ['GET', 'POST'])]
  public function pathEdit(Request $request, Path $path): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(PathType::class, $path);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $symbol = $form->get('symbol')->getData();
      $rune = $form->get('rune')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $path->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $path->setSymbol($this->dataService->upload($symbol, $filePath));
      }
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $path->setRune($this->dataService->upload($rune, $filePath));
      }
      $this->dataService->update($path);

      return $this->redirectToRoute('mage_paths', ['_fragment' => $path->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/path/form.html.twig', [
      'form' => $form,
    ]);
  }


  #[Route('/wiki/legacies', name: 'mage_legacies', methods: ['GET'])]
  public function legacies(): Response
  {
    return $this->render('mage/legacy/index.html.twig', [
      'legacies' => $this->dataService->findBy(Legacy::class, ['homebrewFor' => null], ['name' => "ASC"]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'legacy']),
    ]);
  }

  #[Route("/wiki/legacies/list/{filter<\w+>}/{id<\w+>}", name: "mage_legacies_list", methods: ["GET"])]
  public function legaciesList(string $filter, int $id): Response
  {
    $legacies = $this->dataService->getList($filter, $id, Legacy::class, 'getLegacies');

    return $this->render('mage/legacy/index.html.twig', [
      'setting' => "mage",
      'legacies' => $legacies,
      'filter' => $filter,
      'id' => $id,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'legacy']),
    ]);
  }


  #[Route('/wiki/legacy/{id<\d+>}', name: 'mage_legacy_show', methods: ['GET'])]
  public function legacyShow(Legacy $legacy): Response
  {
    return $this->render('mage/legacy/show.html.twig', [
      'legacy' => $legacy,
    ]);
  }

  #[Route('/legacy/new', name: 'mage_legacy_new', methods: ['GET', 'POST'])]
  public function legacyNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $legacy = new Legacy($this->dataService->getItem($request->get('filter'), $request->get('id')));
    $form = $this->createForm(LegacyType::class, $legacy);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $legacy->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      $this->dataService->save($legacy);

      return $this->redirectToRoute('mage_legacies', ['_fragment' => $legacy->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/legacy/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/legacy/{id<\d+>}/edit', name: 'mage_legacy_edit', methods: ['GET', 'POST'])]
  public function legacyEdit(Request $request, Legacy $legacy): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(LegacyType::class, $legacy);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $filePath = $this->getParameter('paths_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $legacy->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      $this->dataService->update($legacy);

      return $this->redirectToRoute('mage_legacies', ['_fragment' => $legacy->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/legacy/form.html.twig', [
      'form' => $form,
    ]);
  }
}
