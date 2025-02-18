<?php

namespace App\Controller;

use App\Entity\Arcanum;
use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Description;
use App\Entity\MageSpell;
use App\Form\Mage\ArcanumType;
use App\Form\Mage\MageSpellType;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}')]
class ArcanumController extends AbstractController
{
  private DataService $dataService;
  private MageService $service;

  public function __construct(DataService $dataService, MageService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/arcana', name: 'arcanum_index')]
  public function index(): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findBy(Arcanum::class, [], ['name' => 'ASC']),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}', name: 'arcanum_show')]
  public function show(Arcanum $arcanum): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findAll(Arcanum::class),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}/edit', name: 'arcanum_edit', methods:["GET", "POST"])]
  public function edit(Request $request, Arcanum $arcanum): Response
  {
    $form = $this->createForm(ArcanumType::class, $arcanum);
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

  #[Route('/spells', name: 'mage_spell_index')]
  public function indexSpell(): Response
  {
    return $this->render('mage/spell/index.html.twig', [
      'spells' => $this->dataService->findBy(MageSpell::class, [], ['name' => 'ASC']),
    ]);
  }

  #[Route("spell/list/{type<\w+>}/{id<\d+>}", name: "mage_spell_list", methods: ["GET"])]
  public function list(string $type = null, int|string $id = null) : Response
  {
    $spells = $this->dataService->getList($type, $id, MageSpell::class, 'getSpells');

    return $this->render('mage/spell/index.html.twig', [
      'setting' => "mage",
      'spells' => $spells,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'spell']),
    ]);
  }

  #[Route('/spell/{id<\d+>}', name: 'mage_spell_show')]
  public function showSpell(Request $request, MageSpell $spell): Response
  {
    return $this->render('mage/spell/show.html.twig', [
      'spell' => $spell,
      'back' => ['path' => $request->getSession()->get('ms_referer')],
    ]);
  }

  #[Route('/spell/new', name: 'mage_spell_new', methods: ['GET', 'POST'])]
  public function spellNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $spell = new MageSpell();
    $form = $this->createForm(MageSpellType::class, $spell);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($spell);

      $this->addFlash('success', ["general.new.done", ['%name%' => $spell->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $spell->getId()]);
    }

    return $this->render('mage/spell/new.html.twig', [
      'action' => 'new',
      'form' => $form,
    ]);
  }

  #[Route('/spell/{id<\d+>}/edit', name: 'mage_spell_edit', methods:["GET", "POST"])]
  public function editSpell(Request $request, MageSpell $spell): Response
  {
    $form = $this->createForm(MageSpellType::class, $spell);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($spell);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $spell->getName()]]);
      return $this->redirectToRoute('mage_spell_show', ['id' => $spell->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'setting' => 'mage',
      'element' => 'spell',
      'entity' => $spell,
      'form' => $form,
    ]);
  }
}
