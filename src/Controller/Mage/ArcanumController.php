<?php

declare(strict_types=1);

namespace App\Controller\Mage;

use App\Entity\Arcanum;
use App\Entity\Description;
use App\Entity\MageArcanum;
use App\Entity\MageOrder;
use App\Entity\MageSpell;
use App\Entity\MageSpellArcanum;
use App\Entity\SpellRote;
use App\Form\Mage\ArcanumForm;
use App\Form\Mage\MageSpellForm;
use App\Form\Mage\SpellRoteForm;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/mage')]
class ArcanumController extends AbstractController
{
  private DataService $dataService;
  private MageService $service;

  public function __construct(DataService $dataService, MageService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/arcana', name: 'mage_arcanum_index')]
  public function index(): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findBy(Arcanum::class, [], ['name' => 'ASC']),
    ]);
  }

  #[Route('/wiki/arcanum/{id<\d+>}', name: 'arcanum_show')]
  public function show(Arcanum $arcanum): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findAll(Arcanum::class),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}/edit', name: 'mage_arcanum_edit', methods:["GET", "POST"])]
  public function edit(Request $request, Arcanum $arcanum): Response
  {
    $form = $this->createForm(ArcanumForm::class, $arcanum);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($arcanum);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $arcanum->getName()]]);
      return $this->redirectToRoute('arcanum_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'setting' => 'mage',
      'element' => 'arcanum',
      'entity' => $arcanum,
      'form' => $form,
    ]);
  }

  #[Route('/wiki/spells', name: 'mage_spell_index')]
  public function indexSpell(): Response
  {
    $spells = $this->dataService->findBy(MageSpell::class, ['homebrewFor' => null], ['name' => 'ASC']);

    usort($spells, function (MageSpell $spell1, MageSpell $spell2) {
      return ($spell2->getLevel() < $spell1->getLevel()) ? 1 : -1;
    });

    usort($spells, function (MageSpell $spell1, MageSpell $spell2) {
      return ($spell2->getName() < $spell1->getName()) ? 1 : -1;
    });

    return $this->render('mage/spell/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'mage_spell']),
      'mageOrders' => $this->dataService->findAll(MageOrder::class),
      'arcana' => $this->dataService->findAll(Arcanum::class),
      'spells' => $spells,
    ]);
  }

  #[Route("/wiki/spells/list/{filter<\w+>}/{id<\w+>}", name: "mage_spell_list", methods: ["GET"])]
  public function list(?string $filter = null, int|string|null $id = null) : Response
  {
    $spells = $this->dataService->getList($filter, $id, MageSpell::class, 'getSpells')->toArray();

    usort($spells, function (MageSpell $spell1, MageSpell $spell2) {
      return ($spell2->getLevel() < $spell1->getLevel()) ? 1 : -1;
    });

    return $this->render('mage/spell/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'spell']),
      'setting' => "mage",
      'spells' => $spells,
      'filter' => $filter,
      'id' => $id,
    ]);
  }

  #[Route('/wiki/spell/{id<\d+>}', name: 'mage_spell_show')]
  public function showSpell(MageSpell $spell): Response
  {
    return $this->render('mage/spell/show.html.twig', [
      'spell' => $spell,
    ]);
  }

  #[Route('/spell/new', name: 'mage_spell_new', methods: ['GET', 'POST'])]
  public function newSpell(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    
    $spell = new MageSpell($this->dataService->getItem($request->get('filter'), $request->get('id')));
    new MageSpellArcanum($spell);
    $form = $this->createForm(MageSpellForm::class, $spell);
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($spell);

      $this->addFlash('success', ["general.new.done", ['%name%' => $spell->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $spell->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/spell/form.html.twig', [
      'action' => 'new',
      'form' => $form,
    ]);
  }

  #[Route('/spell/{id<\d+>}/edit', name: 'mage_spell_edit', methods:["GET", "POST"])]
  public function editSpell(Request $request, MageSpell $spell): Response
  {
    $form = $this->createForm(MageSpellForm::class, $spell);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($spell);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $spell->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $spell->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/spell/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }

  #[Route('/spell/{spell<\d+>}/rote/new', name: 'mage_spell_rote_new', methods: ['GET', 'POST'])]
  public function newRote(Request $request, MageSpell $spell): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    
    $rote = new SpellRote($spell);
    $form = $this->createForm(SpellRoteForm::class, $rote);
    
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($rote);

      $this->addFlash('success', ["general.new.done", ['%name%' => $rote->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $spell->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/form.html.twig', [
      'action' => 'new',
      'form' => $form,
    ]);
  }

  #[Route('/spell/rote/{id<\d+>}/edit', name: 'mage_spell_rote_edit', methods:["GET", "POST"])]
  public function editRote(Request $request, SpellRote $rote): Response
  {
    $form = $this->createForm(SpellRoteForm::class, $rote);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($rote);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $rote->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $rote->getSpell()->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('mage/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/fetch", name:"mage_spell_fetch", methods:["GET"])]
  public function fetch(MageSpell $spell): Response
  {
    return $this->render('mage/spell/_card.html.twig', [
      'element' => 'spell',
      'spell' => $spell,
    ]);
  }
}
