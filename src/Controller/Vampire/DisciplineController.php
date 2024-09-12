<?php declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;

use App\Form\Vampire\DisciplinePowerType;
use App\Form\Vampire\DisciplineType;

use App\Service\DataService;
use App\Service\VampireService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class DisciplineController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }
  
  #[Route('/disciplines', name: 'vampire_discipline_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    $data = $this->service->getDisciplines();

    return $this->render($data['template'], [
      'elements' => $data['disciplines'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'type' => 'discipline',
    ]);
  }

  #[Route('/sorceries', name: 'vampire_sorcery_index', methods: ['GET'])]
  public function sorceries(): Response
  {
    $data = $this->service->getDisciplines('sorcery');

    return $this->render($data['template'], [
      'elements' => $data['disciplines'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'type' => 'sorcery',
      'icon' => 'pentacle',
      'label' => 'sorcery.label.multi',
    ]);
  }

  #[Route('/coils', name: 'vampire_coils_index', methods: ['GET'])]
  public function coils(): Response
  {
    $data = $this->service->getDisciplines('coils');

    return $this->render($data['template'], [
      'elements' => $data['disciplines'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'type' => 'coils',
      'label' => 'coil.label.multi',
    ]);
  }

  #[Route('/thaumaturgy', name: 'vampire_thaumaturgy_index', methods: ['GET'])]
  public function thaumaturgy(): Response
  {
    $data = $this->service->getDisciplines('thaumaturgy');

    return $this->render($data['template'], [
      'elements' => $data['disciplines'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'type' => 'thaumaturgy',
    ]);
  }

  #[Route('/discipline/{id<\d+>}', name: 'discipline_show', methods: ['GET'])]
  public function discipline(Discipline $discipline): Response
  {
    return $this->render('vampire/discipline/show.html.twig', [
      'discipline' => $discipline,
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
      'action' => 'new',
      'form' => $form,
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
      'action' => 'new',
      'form' => $form,
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
      'action' => 'new',
      'form' => $form,
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
      'action' => 'new',
      'form' => $form,
    ]);
  }

  #[Route('/discipline/{id<\d+>}/edit', name: 'discipline_edit', methods: ['GET', 'POST'])]
  public function disciplineEdit(Request $request, Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DisciplineType::class, $discipline);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($discipline);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }

  #[Route('/discipline/{id<\d+>}/power/add', name: 'discipline_power_add', methods: ['GET', 'POST'])]
  public function disciplinePowerAdd(Request $request, Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    if (!$discipline->isSorcery()) {
      $power = new DisciplinePower($discipline, $discipline->getMaxLevel() + 1);
      $power->setBook($discipline->getBook());
      $power->setHomebrewFor($discipline->getHomebrewFor());
    } else {
      $power = new DisciplinePower($discipline, 1);
    }
    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($power);

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/power.add.html.twig', [
      'action' => 'new',
      'power' => $power,
      'form' => $form,
    ]);
  }

  #[Route('/discipline/power/{id<\d+>}/edit', name: 'discipline_power_edit', methods: ['GET', 'POST'])]
  public function disciplinePowerEdit(Request $request, DisciplinePower $power): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($power);
      /** @var Discipline */
      $discipline = $power->getDiscipline();

      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/power.edit.html.twig', [
      'action' => 'edit',
      'power' => $power,
      'form' => $form,
    ]);
  }

  #[Route("/ritual/{filter<\w+>}/{id<\d+>}", name: "ritual_list", methods: ["GET"])]
  public function ritualFilter(string $filter, int $id): Response
  {
    $data = $this->service->getRituals($filter, $id);

    return $this->render('vampire/discipline/powers/rituals.html.twig', [
      'rituals' => $data['rituals'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'type' => $data['type'],
    ]);
  }

  #[Route("/discipline/{type<\w+>}/{filter<\w+>}/{id<\d+>}", name: "discipline_filter", methods: ["GET"])]
  public function disciplineFilter(string $type, string $filter, int $id): Response
  {
    $data = $this->service->getDisciplines($type, $filter, $id);

    return $this->render($data['template'], [
      'elements' => $data['disciplines'],
      'description' => $data['description'],
      'entity' => 'discipline',
      'back' => $data['back'],
      'type' => $data['type'],
      'icon' => $data['icon'],
      'label' => $data['label'],
    ]);
  }
}