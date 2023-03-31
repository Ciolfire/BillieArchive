<?php

namespace App\Controller\Wiki;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/wiki')]
class BookController extends AbstractController
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route('/books/{setting}', name: 'book_index', methods: ['GET'])]
  public function books($setting = "human"): Response
  {
    $search = [];
    // $search['setting'] = [$setting];
    $types = $this->dataService->getBookTypes($setting);
    if (count($types) > 1) {
      $search['type'] = $types;
    }

    return $this->render('wiki/list.html.twig', [
      'elements' => $this->dataService->findBy(Book::class, ['setting' => $setting], ['displayFirst' => 'DESC', 'name' => 'ASC']),
      'entity' => 'book',
      'category' => 'character',
      'type' => $setting,
      'footer' => true,
      'search' => $search,
    ]);
  }

  #[Route('/book/new/{setting}', name: 'book_new', methods: ['GET', 'POST'])]
  public function bookNew(Request $request, $setting="human"): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $book = new Book($setting);
    $form = $this->createForm(BookType::class, $book);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $cover = $form->get('cover')->getData();
      if (!is_null($cover)) {
        $book->setCover($this->dataService->upload($cover, $this->getParameter('books_cover_directory')));
      }
      $this->dataService->save($book);

      return $this->redirectToRoute('book_index', ['setting' => $book->getSetting()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('wiki/form.html.twig', [
      'entity' => 'book',
      'action' => 'new',
      'form' => $form,
      'type' => $setting,
    ]);
  }

  #[Route('/book/{id<\d+>}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
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

    return $this->render('wiki/form.html.twig', [
      'entity' => 'book',
      'action' => 'edit',
      'form' => $form,
      'type' => $book->getSetting(),
    ]);
  }
}
