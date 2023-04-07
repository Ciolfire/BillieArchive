<?php

namespace App\Controller;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Entity\User;
use App\Form\NoteSearchType;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Service\DataService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale<%supported_locales%>?%default_locale%}")]
class NoteController extends AbstractController
{
  private $doctrine;
  private $dataService;

  public function __construct(ManagerRegistry $doctrine, DataService $dataService)
  {
    $this->doctrine = $doctrine;
    $this->dataService = $dataService;
  }

  #[Route('chronicle/{id<\d+>}/note/new', name: 'chronicle_note_new', methods: ['GET', 'POST'])]
  public function addNote(Request $request, Chronicle $chronicle): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $note = new Note();
    $note->setUser($user);
    $note->setChronicle($chronicle);
    /** @var NoteRepository $repo */
    $repo = $this->doctrine->getRepository(Note::class);
    $linkableNotes = $repo->findByLinkable($user, $note);
    // Set up date based on chronicle date
    $form = $this->createForm(NoteType::class, $note, [
      'categories' => $this->dataService->findBy(NoteCategory::class, ['chronicle' => $chronicle, 'user' => $user]),
      'notes' => [$linkableNotes],
    ]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $user->addNote($note);
      $this->dataService->save($note);

      if ($note->getCategory() instanceof NoteCategory) {

        return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => $note->getCategory()->getId()], Response::HTTP_SEE_OTHER);
      } else {

        return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => null], Response::HTTP_SEE_OTHER);
      }
    }
    return $this->render('notes/form.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/note/{id<\d+>}/edit', name: 'note_edit', methods: ['GET', 'POST'])]
  public function editNote(Request $request, Note $note): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    /** @var NoteRepository $repo */
    $repo = $this->doctrine->getRepository(Note::class);
    $linkableNotes = $repo->findByLinkable($user, $note);
    $form = $this->createForm(NoteType::class, $note, [
      'categories' => $this->dataService->findBy(NoteCategory::class, ['chronicle' => $note->getChronicle(), 'user' => $user]),
      'notes' => [$linkableNotes],
    ]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();
      if ($note->getCategory() instanceof NoteCategory) {

        return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => $note->getCategory()->getId()], Response::HTTP_SEE_OTHER);
      } else {

        return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => null], Response::HTTP_SEE_OTHER);
      }

    }

    return $this->render('notes/form.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/note/{id<\d+>}/delete', name: 'note_delete', methods: ['GET', 'DELETE'])]
  public function deleteNote(Request $request, Note $note): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $category = $note->getCategory();
    /** @var NoteRepository $repo */
    $this->dataService->remove($note);

    if ($category instanceof NoteCategory) {

      return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => $category->getId()], Response::HTTP_SEE_OTHER);
    } else {

      return $this->redirectToRoute('chronicle_notes', ['id' => $note->getChronicle()->getId(), 'category' => null], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('chronicle/note/{id<\d+>}', name: 'chronicle_note_view', methods: ['GET'])]
  public function viewNote(Note $note): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $notes = null;
    $categories = $this->dataService->findBy(NoteCategory::class, ['chronicle' => $note->getChronicle(), 'user' => $user]);
    $category = $note->getCategory();

    if ($category instanceof NoteCategory) {
      $notes = $this->dataService->findBy(Note::class, ['chronicle' => $note->getChronicle(), 'user' => $user, 'category' => $category]);
    }

    return $this->render('notes/chronicle/index.html.twig', [
      'chronicle' => $note->getChronicle(),
      'current' => $category,
      'categories' => $categories,
      'notes' => $notes,
      'currentNote' => $note,
      'setting' => $note->getChronicle()->getType(),
    ]);
  }

  #[Route('/chronicle/{id<\d+>}/notes/{category<\d+>?0}', name: 'chronicle_notes', methods: ['GET'])]
  public function notes(Chronicle $chronicle, ?NoteCategory $category): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $notes = null;
    $categories = $this->dataService->findBy(NoteCategory::class, ['chronicle' => $chronicle, 'user' => $user]);
    
    if ($category instanceof NoteCategory) {
      $notes = $this->dataService->findBy(Note::class, ['chronicle' => $chronicle, 'user' => $user, 'category' => $category]);
    } else {
      $notes = $this->dataService->findBy(Note::class, ['chronicle' => $chronicle, 'user' => $user, 'category' => null]);
    }

    return $this->render('notes/chronicle/index.html.twig', [
      'chronicle' => $chronicle,
      'current' => $category,
      'categories' => $categories,
      'notes' => $notes,
      'setting' => $chronicle->getType(),
    ]);
  }

  #[Route('/chronicle/{id<\d+>}/notes/search/', name: 'chronicle_notes_search', methods: ['GET', 'POST'])]
  public function searchNotes(Request $request, Chronicle $chronicle): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $notes = null;
    // Set up date based on chronicle date
    $form = $this->createForm(NoteSearchType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $toSearch = $form->getData()['toFind'];
      $repo = $this->doctrine->getRepository(Note::class);
      if ($repo instanceof NoteRepository) {
        $notes = $repo->findFromSearch($toSearch, $user, $chronicle);
      }
    }
    return $this->render('notes/search.html.twig', [
      'chronicle' => $chronicle,
      'form' => $form,
      'notes' => $notes,
    ]);
  }
}