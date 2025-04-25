<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Character;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Entity\Vampire;
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

  #[Route('/wiki/disciplines', name: 'vampire_discipline_index', methods: ['GET'])]
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

  #[Route('/wiki/sorceries', name: 'vampire_sorcery_index', methods: ['GET'])]
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

  #[Route('/wiki/coils', name: 'vampire_coils_index', methods: ['GET'])]
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

  #[Route('/wiki/thaumaturgy', name: 'vampire_thaumaturgy_index', methods: ['GET'])]
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

  #[Route("/wiki/{type<\w+>?discipline}/list/{filter<\w+>}/{id<\w+>}", name: "vampire_discipline_list", methods: ["GET"])]
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

  #[Route('/wiki/discipline/{id<\d+>}', name: 'discipline_show', methods: ['GET'])]
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

      $this->addFlash('success', ["general.new.done", ['%name%' => $discipline->getName()]]);
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

      $this->addFlash('success', ["general.new.done", ['%name%' => $discipline->getName()]]);
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

      $this->addFlash('success', ["general.new.done", ['%name%' => $discipline->getName()]]);
      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/new.html.twig', [
      'action' => 'new',
      'form' => $form,
    ]);
  }

  #[Route('/coil/new', name: 'vampire_coils_new', methods: ['GET', 'POST'])]
  public function coilsNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $discipline = new Discipline(false, false, true);

    $form = $this->createForm(DisciplineType::class, $discipline);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($discipline);

      $this->addFlash('success', ["general.new.done", ['%name%' => $discipline->getName()]]);
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

      $this->addFlash('success', ["general.edit.done", ['%name%' => $discipline->getName()]]);
      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }

  #[Route('/discipline/{id<\d+>}/delete', name: 'vampire_discipline_delete', methods: ['GET'])]
  public function delete(Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('delete', $discipline);

    try {
      $this->dataService->remove($discipline);
      $this->addFlash('success', ["discipline.delete.success", ['%name%' => $discipline->getName()]]);

      return $this->redirectToRoute('vampire_discipline_index');
    } catch (\Throwable $th) {
      $this->addFlash('error', ["discipline.delete.failed", ['%name%' => $discipline->getName()]]);
    }


    return $this->redirectToRoute('vampire_discipline_index');
  }

  #[Route('/discipline/{id<\d+>}/power/add', name: 'vampire_discipline_power_add', methods: ['GET', 'POST'])]
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
      if ($discipline->isSorcery() && $power->getLevel() > 0) {
        $power->setIsRitual(true);
      }
      $this->dataService->save($power);

      $this->addFlash('success', ["general.new.done", ['%name%' => "{$discipline->getName()} — {$power->getName()}"]]);
      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/power.add.html.twig', [
      'action' => 'new',
      'power' => $power,
      'form' => $form,
    ]);
  }

  #[Route('/discipline/power/{id<\d+>}/edit', name: 'vampire_discipline_power_edit', methods: ['GET', 'POST'])]
  public function disciplinePowerEdit(Request $request, DisciplinePower $power): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      foreach ($power->getStatusEffects() as $status) {
        if ($power->getName()) {
          $status->setName($power->getName());
        } else {
          $status->setName($power->getDiscipline()->getName());
        }
        $status->setDisciplinePower($power);
        $status->setIcon('discipline');
      }
      // $this->service->setupPowerStatus();
      $this->dataService->update($power);
      /** @var Discipline */
      $discipline = $power->getDiscipline();

      $this->addFlash('success', ["general.edit.done", ['%name%' => "{$discipline->getName()} — {$power->getName()}"]]);
      return $this->redirectToRoute('discipline_show', ['id' => $discipline->getId()]);
    }

    return $this->render('vampire/discipline/power.edit.html.twig', [
      'action' => 'edit',
      'power' => $power,
      'form' => $form,
    ]);
  }

  #[Route('/discipline/power/{id<\d+>}/{character<\d+>}/toggle/{activate}', name: 'vampire_discipline_power_toggle', methods: ['GET'])]
  public function disciplinePowerToggle(Character $character, DisciplinePower $power, bool $activate): Response
  {
    if ($activate) {
      if ($character instanceof Vampire) {
        foreach ($power->getStatusEffects() as $effect) {
          $newEffect = clone $effect;
          $character->addStatusEffect($newEffect);
          $this->dataService->add($newEffect);
        }
      }
    } else {
      foreach ($character->getStatusEffects() as $effect) {
        if ($effect->getDisciplinePower() === $power) {
          $this->dataService->delete($effect);
        }
      }
    }
    $this->dataService->flush();
    
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }


  #[Route('/discipline/power/{id<\d+>}/delete', name: 'vampire_discipline_power_delete', methods: ['GET'])]
  public function deletePower(DisciplinePower $disciplinePower): Response
  {
    $this->denyAccessUnlessGranted('delete', $disciplinePower);

    try {
      $this->dataService->remove($disciplinePower);
      $this->addFlash('success', ["discipline.power.delete.success", ['%name%' => $disciplinePower->getName()]]);

      return $this->redirectToRoute('vampire_discipline_show', ['id' => $disciplinePower->getDiscipline()->getId()]);
    } catch (\Throwable $th) {
      $this->addFlash('error', ["discipline.power.delete.failed", ['%name%' => $disciplinePower->getName()]]);
    }


    return $this->redirectToRoute('vampire_discipline_show', ['id' => $disciplinePower->getDiscipline()->getId()]);
  }

  #[Route("/wiki/ritual/{filter<\w+>}/{id<\d+>}", name: "vampire_ritual_list", methods: ["GET"])]
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
}
