<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Book;
use App\Entity\Skill;
use App\Form\AttributeType;
use App\Form\BookType;
use App\Form\SkillType;
use App\Repository\AttributeRepository;
use App\Repository\SkillRepository;
use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
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

  #[Route('/books/{setting}', name: 'book_index', methods: ['GET'])]
  public function books($setting="human"): Response
  {
    
    return $this->render('wiki/list.html.twig', [
      'elements' => $this->dataService->findBy(Book::class, ['setting' => $setting]),
      'entity' => 'book',
      'category' => 'character',
      'type' => $setting,
    ]);
  }

  #[Route('/book/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
  public function bookEdit(Request $request, Book $book): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $cover = $form->get('cover')->getData();
      if (!is_null($cover)) {
        $book->setCover($this->dataService->upload($cover, $this->getParameter('books_cover_directory')));
      }
      $this->dataService->flush();

      return $this->redirectToRoute('book_index', ['setting' => $book->getSetting()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'book',
      'form' => $form,
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
    ]);
  }

  #[Route('/attribute/{id}/edit', name: 'attribute_edit', methods: ['GET', 'POST'])]
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
    ]);
  }

  #[Route('/skill/{id}/edit', name: 'skill_edit', methods: ['GET', 'POST'])]
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