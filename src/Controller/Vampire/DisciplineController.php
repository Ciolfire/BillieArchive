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
  #[Route('/disciplines', name: 'discipline_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    return $this->render('vampire/discipline/index.html.twig', [
      'elements' => $this->dataService->findAll(Discipline::class),
      'description' => $this->dataService->findBy(Description::class, ['name' => 'discipline']),
      'entity' => 'discipline',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/{id<\d+>}', name: 'discipline_show', methods: ['GET'])]
  public function discipline(Discipline $discipline): Response
  {
    return $this->renderForm('vampire/discipline/show.html.twig', [
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

    return $this->renderForm('vampire/discipline/new.html.twig', [
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

    return $this->renderForm('vampire/discipline/new.html.twig', [
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

    return $this->renderForm('vampire/discipline/power.add.html.twig', [
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

    return $this->renderForm('vampire/discipline/power.edit.html.twig', [
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