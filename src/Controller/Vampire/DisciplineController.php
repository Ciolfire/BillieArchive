<?php

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Description;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;

use App\Form\DisciplinePowerType;
use App\Form\DisciplineType;

use App\Service\DataService;
use App\Service\VampireService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class DisciplineController extends AbstractController
{
  private $dataService;
  private $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }
  
  #[Route('/disciplines', name: 'vampire_discipline_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    $disciplines = $this->dataService->findBy(Discipline::class, [
      'isSorcery' => false,
      'isThaumaturgy' => false,
      'isCoil' => false,
    ]);

    return $this->render('vampire/discipline/index.html.twig', [
      'elements' => $disciplines,
      'description' => $this->dataService->findBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'discipline',
      'type' => 'vampire',
    ]);
  }

  #[Route('/sorcery', name: 'vampire_sorcery_index', methods: ['GET'])]
  public function sorcery(): Response
  {
    $disciplines = $this->dataService->findBy(Discipline::class, ['isSorcery' => true]);

    return $this->render('vampire/discipline/index.html.twig', [
      'elements' => $disciplines,
      'description' => $this->dataService->findBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'sorcery',
      'type' => 'vampire',
    ]);
  }

  #[Route('/thaumaturgy', name: 'vampire_thaumaturgy_index', methods: ['GET'])]
  public function thaumaturgy(): Response
  {
    $disciplines = $this->dataService->findBy(Discipline::class, ['isThaumaturgy' => true]);

    return $this->render('vampire/discipline/index.html.twig', [
      'elements' => $disciplines,
      'description' => $this->dataService->findBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'thaumaturgy',
      'type' => 'vampire',
    ]);
  }

  #[Route('/coils', name: 'vampire_coils_index', methods: ['GET'])]
  public function coils(): Response
  {
    $disciplines = $this->dataService->findBy(Discipline::class, ['isCoil' => true]);

    return $this->render('vampire/discipline/index.html.twig', [
      'elements' => $disciplines,
      'description' => $this->dataService->findBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'coils',
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/{id<\d+>}', name: 'discipline_show', methods: ['GET'])]
  public function discipline(Discipline $discipline): Response
  {
    return $this->render('vampire/discipline/show.html.twig', [
      'discipline' => $discipline,
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/new', name: 'vampire_discipline_new', methods: ['GET', 'POST'])]
  public function disciplineNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $discipline = new Discipline();
    $form = $this->createForm(DisciplineType::class, $discipline);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($discipline);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/sorcery/new', name: 'vampire_sorcery_new', methods: ['GET', 'POST'])]
  public function sorceryNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $discipline = new Discipline(true);

    $form = $this->createForm(DisciplineType::class, $discipline);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($discipline);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/thaumaturgy/new', name: 'vampire_thaumaturgy_new', methods: ['GET', 'POST'])]
  public function thaumaturgyNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $discipline = new Discipline(false, true);

    $form = $this->createForm(DisciplineType::class, $discipline);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($discipline);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/coils/new', name: 'vampire_coils_new', methods: ['GET', 'POST'])]
  public function coilsNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $discipline = new Discipline(false, false, true);

    $form = $this->createForm(DisciplineType::class, $discipline);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($discipline);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/{id<\d+>}/edit', name: 'discipline_edit', methods: ['GET', 'POST'])]
  public function disciplineEdit(Request $request, Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DisciplineType::class, $discipline);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/{id<\d+>}/power/add', name: 'discipline_power_add', methods: ['GET', 'POST'])]
  public function disciplinePowerAdd(Request $request, Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $power = new DisciplinePower($discipline, $discipline->getMaxLevel() + 1);
    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($power);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/power.add.html.twig', [
      'power' => $power,
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/power/{id<\d+>}/edit', name: 'discipline_power_edit', methods: ['GET', 'POST'])]
  public function disciplinePowerEdit(Request $request, DisciplinePower $power): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($power);

      return $this->redirectToRoute('discipline_show', ['id' => $power->getDiscipline()->getId()]);
    }

    return $this->render('vampire/discipline/power.edit.html.twig', [
      'power' => $power,
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route("/discipline/{type<\w+>}/{id<\d+>}", name: "discipline_list", methods: ["GET"])]
  public function clanList($type, $id)
  {
    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        break;
      
      default:
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        break;
    }

    return $this->render('vampire/discipline/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'character',
      'type' => 'vampire',
      'elements' => $item->getDisciplines(),
      'search' => [],
    ]);
  }
}