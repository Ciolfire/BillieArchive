<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Skill;
use App\Form\AttributeType;
use App\Form\SkillType;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/wiki')]
class WikiController extends AbstractController
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route('/', name: 'wiki_index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('wiki/index.html.twig', [
      'type' => 'human',
    ]);
  }

  #[Route('/attributes', name: 'attribute_index', methods: ['GET'])]
  public function attributes(): Response
  {
    
    return $this->render('wiki/list.html.twig', [
      'elements' => $this->dataService->findAll(Attribute::class),
      'entity' => 'attribute',
      'category' => 'character',
      'type' => 'human',
      'isFixed' => true
    ]);
  }

  #[Route('/attribute/{id<\d+>}/edit', name: 'attribute_edit', methods: ['GET', 'POST'])]
  public function attributeEdit(Request $request, Attribute $attribute): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(AttributeType::class, $attribute);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('attribute_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'attribute',
      'form' => $form,
    ]);
  }

  #[Route('/skills', name: 'skill_index', methods: ['GET'])]
  public function skills(): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $this->dataService->findAll(Skill::class),
      'entity' => 'skill',
      'category' => 'character',
      'type' => 'human',
      'isFixed' => true
    ]);
  }

  #[Route('/skill/{id<\d+>}/edit', name: 'skill_edit', methods: ['GET', 'POST'])]
  public function skillEdit(Request $request, Skill $skill): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(SkillType::class, $skill);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('skill_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'attribute',
      'form' => $form,
    ]);
  }
}