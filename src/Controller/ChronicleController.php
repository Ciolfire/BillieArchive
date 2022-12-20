<?php

namespace App\Controller;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Entity\User;
use App\Form\ChronicleType;
use App\Form\NoteCategoryType;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use App\Service\CharacterService;
use App\Service\DataService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale<%supported_locales%>?%default_locale%}/chronicle")]
class ChronicleController extends AbstractController
{
  private $doctrine;
  private $service;
  private $dataService;

  public function __construct(ManagerRegistry $doctrine, CharacterService $service, DataService $dataService)
  {
    $this->doctrine = $doctrine;
    $this->service = $service;
    $this->dataService = $dataService;
  }

  #[Route("/new", name: "chronicle_new")]
  public function new(Request $request) : Response
  {
    $chronicle = new Chronicle();
    $form = $this->createForm(ChronicleType::class, $chronicle);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $chronicle->setStoryteller($this->getUser());
      $this->doctrine->getManager()->persist($chronicle);
      $this->doctrine->getManager()->flush();
      $this->addFlash('notice', "{$chronicle->getName()} created");
      return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
    }

    return $this->renderForm('chronicle/new.html.twig', [
      'chronicle' => $chronicle,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/", name: "chronicle_show", methods: ["GET"])]
  public function show(Chronicle $chronicle)
  {
    return $this->render('chronicle/show.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/homebrew", name: "homebrew_index", methods: ["GET"])]
  public function homebrew(Chronicle $chronicle)
  {
    return $this->render('chronicle/homebrew/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/party", name: "chronicle_party_index", methods: ["GET"])]
  public function party(Chronicle $chronicle)
  {
    return $this->render('chronicle/party/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/npc", name: "chronicle_npc_index", methods: ["GET"])]
  public function npc(Chronicle $chronicle)
  {
    return $this->render('character/npc/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/npc/add", name: "chronicle_npc_add", methods: ["GET"])]
  public function addNpc(Chronicle $chronicle)
  {

    return $this->redirectToRoute('character_new', ['chronicle' => $chronicle->getId(), 'isNpc' => true]);
  }

  #[Route("/{id<\d+>}/party/add", name: "chronicle_add_player")]
  public function addPlayer(Request $request, Chronicle $chronicle) : Response
  {
    /** @var UserRepository */
    $userRepository = $this->doctrine->getRepository(User::class);
    $availablePlayers = $userRepository->getAvailablePlayersForChronicle($chronicle->getStoryteller(), $chronicle->getPlayers());
    if ($availablePlayers) {
      $form = $this->createFormBuilder()
        ->add('player', ChoiceType::class, [
          'choices' => $availablePlayers,
          'choice_label' => 'username',
        'choice_value' => 'id',
        ])
      ->add('submit', SubmitType::class)
      ->getForm();
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        /** @var User */
        $player = $form->getData()['player'];
        $chronicle->addPlayer($player);
        $this->doctrine->getManager()->flush();
        $this->addFlash('notice', "{$player->getUserIdentifier()} added to the campaign");
        return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
      }
    } else {
      $this->addFlash('notice', 'No player available');
      return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
    }

    return $this->renderForm('chronicle/party/playerChange.html.twig', [
      'chronicle' => $chronicle,
      'action' => 'add',
      'type' => $chronicle->getType(),
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/party/remove/player", name: "chronicle_remove_player")]
  public function removePlayer(Request $request, Chronicle $chronicle) : Response
  {
    $availablePlayers = $chronicle->getPlayers();
    if ($availablePlayers) {
      $form = $this->createFormBuilder()
        ->add('player', ChoiceType::class, [
          'choices' => $availablePlayers,
          'choice_label' => 'username',
        'choice_value' => 'id',
        ])
      ->add('submit', SubmitType::class)
      ->getForm();
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        /** @var User */
        $player = $form->getData()['player'];
        $chronicle->removePlayer($player);
        $this->doctrine->getManager()->flush();
        $this->addFlash('notice', "{$player->getUserIdentifier()} removed from the campaign");
        return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
      }
    } else {
      $this->addFlash('notice', 'No player available');
      return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
    }

    return $this->renderForm('chronicle/party/playerChange.html.twig', [
      'chronicle' => $chronicle,
      'action' => 'remove',
      'type' => $chronicle->getType(),
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/note/new', name: 'chronicle_note_new', methods: ['GET', 'POST'])]
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
    return $this->renderForm('notes/form.html.twig', [
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

    return $this->renderForm('notes/form.html.twig', [
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

  #[Route('/{id<\d+>}/note/category/new', name: 'chronicle_note_category_new', methods: ['GET', 'POST'])]
  public function addNoteCategory(Request $request, Chronicle $chronicle): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $category = new NoteCategory();
    $category->setUser($user);
    $category->setChronicle($chronicle);

    // Set up date based on chronicle date
    $form = $this->createForm(NoteCategoryType::class, $category);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($category);

      return $this->redirectToRoute('chronicle_notes', ['id' => $chronicle->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('notes/category.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  #[Route('{id<\d+>}/note/category/{category<\d+>}/edit', name: 'chronicle_note_category_edit', methods: ['GET', 'POST'])]
  public function editNoteCategory(Request $request, Chronicle $chronicle, NoteCategory $category): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    // Set up date based on chronicle date
    $form = $this->createForm(NoteCategoryType::class, $category);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('chronicle_notes', ['id' => $chronicle->getId(), 'category' => $category->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('notes/category.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  #[Route('{id<\d+>}/note/category/{category<\d+>}/delete', name: 'chronicle_note_category_delete', methods: ['GET', 'DELETE'])]
  public function deleteCategory(Request $request, Chronicle $chronicle, NoteCategory $category): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $this->dataService->remove($category);

    return $this->redirectToRoute('chronicle_notes', ['id' => $chronicle->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/notes/{category<\d+>?0}', name: 'chronicle_notes', methods: ['GET'])]
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

    return $this->renderForm('notes/chronicle/index.html.twig', [
      'chronicle' => $chronicle,
      'current' => $category,
      'categories' => $categories,
      'notes' => $notes,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route('/note/{id<\d+>}', name: 'chronicle_note_view', methods: ['GET'])]
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

    return $this->renderForm('notes/chronicle/index.html.twig', [
      'chronicle' => $note->getChronicle(),
      'current' => $category,
      'categories' => $categories,
      'notes' => $notes,
      'currentNote' => $note,
      'type' => $note->getChronicle()->getType(),
    ]);
  }
}