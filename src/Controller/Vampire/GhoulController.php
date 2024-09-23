<?php declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Description;
use App\Entity\GhoulFamily;
use App\Form\Vampire\GhoulFamilyType;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire/ghoul')]
class GhoulController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/families', name: 'ghoul_families_index', methods: ['GET'])]
  public function families(): Response
  {
    return $this->render('vampire/ghoul/family/index.html.twig', [
      'families' => $this->dataService->findBy(GhoulFamily::class, [], ['name' => 'ASC']),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'ghoul_family']),
      'entity' => 'ghoul_family',
      'category' => 'character',
      'search' => [
        'clan' => ['Daeva', 'Gangrel', 'Mekhet', 'Nosferatu', 'Ventrue'],
      ],
    ]);
  }

  #[Route('/family/{id<\d+>}', name: 'ghoul_family_show', methods: ['GET'])]
  public function familyShow(GhoulFamily $family): Response
  {
    return $this->render('vampire/ghoul/family/show.html.twig', [
      'family' => $family,
      'entity' => 'ghoul_family',
    ]);
  }

  #[Route('/family/new', name: 'ghoul_family_new', methods: ['GET', 'POST'])]
  public function familyNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $family = new GhoulFamily();
    $form = $this->createForm(GhoulFamilyType::class, $family);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $path = $this->getParameter('ghoul_family_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($path)) {
        $family->setEmblem($this->dataService->upload($emblem, $path));
      }
      $this->dataService->save($family);

      $this->addFlash('success', ["general.new.done", ['%name%' => $family->getName()]]);
      return $this->redirectToRoute('ghoul_families_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('vampire/ghoul/family/form.html.twig', [
      'action' => 'new',
      'entity' => 'ghoul_family',
      'trans' => 'family.',
      'form' => $form,
    ]);
  }

  #[Route('/family/{id<\d+>}/edit', name: 'ghoul_family_edit', methods: ['GET', 'POST'])]
  public function familyEdit(Request $request, GhoulFamily $family): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(GhoulFamilyType::class, $family);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $path = $this->getParameter('ghoul_family_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($path)) {
        $family->setEmblem($this->dataService->upload($emblem, $path));
      }
      $this->dataService->update($family);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $family->getName()]]);
      return $this->redirectToRoute('ghoul_families_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('vampire/ghoul/family/form.html.twig', [
      'action' => 'edit',
      'entity' => 'ghoul_family',
      'trans' => 'ghoul.family.',
      'form' => $form,
    ]);
  }

  #[Route("/family/{filter<\w+>}/{id<\d+>}", name: "ghoul_family_list", methods: ["GET"])]
  public function familyList(string $filter, int $id): Response
  {
    switch ($filter) {
      case 'chronicle':
        /** @var Chronicle */
        $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
        $back = ['path' => 'homebrew_index', 'params' => [
          'id' => $id,
        ]];
        break;
      case 'book':
      default:
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $back = ['path' => 'book_index', 'params' => [
          'setting' => 'vampire',
          '_fragment' => $id
        ]];
    }

    return $this->render('vampire/ghoul/family/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'ghoul_family']),
      'entity' => 'ghoul_family',
      'category' => 'character',
      'families' => $item->getGhoulFamilies(),
      'search' => [],
      'back' => $back,
    ]);
  }
}
