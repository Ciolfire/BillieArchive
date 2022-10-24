<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Description;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Form\ClanType;
use App\Form\DisciplinePowerType;
use App\Form\DisciplineType;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\DataService;
use App\Service\VampireService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class VampireController extends AbstractController
{
  private $dataService;
  private $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/', name: 'vampire_index', methods: ['GET'])]
  public function vampires(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => false
      ]),
      'npc' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ]),
    ]);
  }

  #[Route('/bloodline/new', name: 'vampire_bloodline_new', methods: ['GET'])]
  public function bloodlineNew(): Response
  {
    return $this->render('vampire/bloodline/new.html.twig', [
      'type' => 'vampire',
    ]);
  }

  #[Route('{id}/bloodline/join', name: 'vampire_bloodline_join', methods: ['GET', 'POST'])]
  public function bloodlineJoin(Request $request, Vampire $vampire): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if ($vampire->getPlayer() != $this->getUser() && ($vampire->getChronicle() && $vampire->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }

    // Form submited
    if ($request->request->get('bloodline')) {
      $vampire->setClan($this->dataService->find(Clan::class, $request->request->get('bloodline')));
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $vampire->getId()], Response::HTTP_SEE_OTHER);
    }

    $bloodlines = $this->dataService->findBy(Clan::class, ['parentClan' => $vampire->getClan()]);
    
    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
      'type' => 'vampire',
    ]);
  }

  #[Route('/clans', name: 'clan_index', methods: ['GET'])]
  public function clans(): Response
  {
    return $this->render('vampire/clan/index.html.twig', [
      'clans' => $this->dataService->findBy(Clan::class, ['parentClan' => null]),
      'bloodlines' => $this->dataService->findBy(Clan::class, ['parentClan' => !null]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan'])->getValue(),
      'entity' => 'clan',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  #[Route('/clan/new', name: 'clan_new', methods: ['GET', 'POST'])]
  public function clanNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $clan = new Clan();

    $form = $this->createForm(ClanType::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($clan);

      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/clan/{id}/edit', name: 'clan_edit', methods: ['GET', 'POST'])]
  public function clanEdit(Request $request, Clan $clan): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(ClanType::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
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

  #[Route('/discipline/{id}', name: 'discipline_show', methods: ['GET'])]
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

  #[Route('/discipline/{id}/edit', name: 'discipline_edit', methods: ['GET', 'POST'])]
  public function disciplineEdit(Request $request, Discipline $discipline): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(DisciplineType::class, $discipline);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('discipline_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('vampire/discipline/new.html.twig', [
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/discipline/{id}/power/add', name: 'discipline_power_add', methods: ['GET', 'POST'])]
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

  #[Route('/discipline/power/{id}/edit', name: 'discipline_power_edit', methods: ['GET', 'POST'])]
  public function disciplinePowerEdit(Request $request, DisciplinePower $power, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(DisciplinePowerType::class, $power);

    $form->handleRequest($request);

    return $this->renderForm('vampire/discipline/power.edit.html.twig', [
      'power' => $power,
      'form' => $form,
      'type' => 'vampire',
    ]);
  }
}
