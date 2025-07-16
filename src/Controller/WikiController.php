<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\ContentType;
use App\Entity\Description;
use App\Entity\Merit;
use App\Entity\Skill;
use App\Entity\Vice;
use App\Entity\Virtue;
use App\Form\AttributeForm;
use App\Form\DescriptionForm;
use App\Form\SkillForm;
use App\Form\ViceForm;
use App\Form\VirtueForm;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/wiki')]
class WikiController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route('', name: 'wiki_index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('wiki/index.html.twig');
  }

  #[Route('/attributes', name: 'attribute_index', methods: ['GET'])]
  public function attributes(): Response
  {
    
    return $this->render('attribute/list.html.twig', [
      'attributes' => $this->dataService->findAll(Attribute::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'attribute']),
    ]);
  }

  #[Route('/attribute/{id<\d+>}/show', name: 'attribute_show', methods: ['GET', 'POST'])]
  public function attributeShow(Request $request, Attribute $attribute): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("attribute/$template.html.twig", [
      'attribute' => $attribute,
    ]);
  }

  #[Route('/attribute/{id<\d+>}/edit', name: 'attribute_edit', methods: ['GET', 'POST'])]
  public function attributeEdit(Request $request, Attribute $attribute): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(AttributeForm::class, $attribute);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($attribute);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $attribute->getName()]]);
      return $this->redirectToRoute('attribute_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('wiki/edit.html.twig', [
      'action' => 'edit',
      'entity' => 'attribute',
      'form' => $form,
    ]);
  }

  #[Route('/skills', name: 'skill_index', methods: ['GET'])]
  public function skills(): Response
  {
    return $this->render('skill/list.html.twig', [
      'skills' => $this->dataService->findAll(Skill::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'skill']),
    ]);
  }

  #[Route('/skill/{id<\d+>}/show', name: 'skill_show', methods: ['GET', 'POST'])]
  public function skillShow(Request $request, Skill $skill): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("skill/$template.html.twig", [
      'skill' => $skill,
    ]);
  }

  #[Route('/skill/{id<\d+>}/edit', name: 'skill_edit', methods: ['GET', 'POST'])]
  public function skillEdit(Request $request, Skill $skill): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(SkillForm::class, $skill);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($skill);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $skill->getName()]]);
      return $this->redirectToRoute('skill_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('wiki/edit.html.twig', [
      'action' => 'edit',
      'entity' => 'skill',
      'form' => $form,
      'footer' => false,
    ]);
  }

  #[Route('/virtues', name: 'virtue_index', methods: ['GET'])]
  public function virtues(): Response
  {
    return $this->render('virtue/list.html.twig', [
      'virtues' => $this->dataService->findAll(Virtue::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'virtue']),
    ]);
  }

  #[Route('/virtue/{id<\d+>}/show', name: 'virtue_show', methods: ['GET', 'POST'])]
  public function virtueShow(Request $request, Virtue $virtue): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("virtue/$template.html.twig", [
      'virtue' => $virtue,
    ]);
  }

  #[Route('/virtue/{id<\d+>}/edit', name: 'virtue_edit', methods: ['GET', 'POST'])]
  public function virtueEdit(Request $request, Virtue $virtue): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(VirtueForm::class, $virtue);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($virtue);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $virtue->getName()]]);
      return $this->redirectToRoute('virtue_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('wiki/edit.html.twig', [
      'action' => 'edit',
      'entity' => 'virtue',
      'domain' => 'character',
      'form' => $form,
      'footer' => false,
    ]);
  }

  #[Route('/vices', name: 'vice_index', methods: ['GET'])]
  public function vices(): Response
  {
    return $this->render('virtue/list.html.twig', [
      'vices' => $this->dataService->findAll(Vice::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'vice']),
    ]);
  }

  #[Route('/vice/{id<\d+>}/show', name: 'vice_show', methods: ['GET', 'POST'])]
  public function viceShow(Request $request, Vice $vice): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("virtue/$template.html.twig", [
      'vice' => $vice,
    ]);
  }

  #[Route('/vice/{id<\d+>}/edit', name: 'vice_edit', methods: ['GET', 'POST'])]
  public function viceEdit(Request $request, Vice $vice): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(ViceForm::class, $vice);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($vice);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $vice->getName()]]);
      return $this->redirectToRoute('vice_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('wiki/edit.html.twig', [
      'action' => 'edit',
      'domain' => 'character',
      'entity' => 'vice',
      'form' => $form,
      'footer' => false,
    ]);
  }

  #[Route("/description/new", name:"description_new", methods:["GET", "POST"])]
  public function new(Request $request): Response
  {
    $description = new Description();

    $form = $this->createForm(DescriptionForm::class, $description);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($description);

      $this->addFlash('success', ["general.new.done", ['%name%' => $description->getName()]]);
      return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'description',
      'entity' => $description,
      'form' => $form,
    ]);
  }

  #[Route("/description/{id<\d+>}/edit", name:"description_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Description $description): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DescriptionForm::class, $description);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($description);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $description->getName()]]);
      return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'description',
      'entity' => $description,
      'form' => $form,
    ]);
  }
}