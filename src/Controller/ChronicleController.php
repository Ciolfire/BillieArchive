<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chronicle;
use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Entity\User;
use App\Form\ChronicleType;
use App\Form\NoteCategoryType;
use App\Form\VampireRulesType;
use App\Repository\UserRepository;
use App\Service\CharacterService;
use App\Service\DataService;
use App\Service\VampireService;
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
  private ManagerRegistry $doctrine;
  private CharacterService $service;
  private DataService $dataService;

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
      /** @var User $user */
      $user = $this->getUser();
      $chronicle->setStoryteller($user);
      $this->doctrine->getManager()->persist($chronicle);
      $this->doctrine->getManager()->flush();
      $this->addFlash('notice', "{$chronicle->getName()} created");
      return $this->redirectToRoute('chronicle_party_index', ['id' => $chronicle->getId()]);
    }

    return $this->render('chronicle/new.html.twig', [
      'chronicle' => $chronicle,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/", name: "chronicle_show", methods: ["GET"])]
  public function show(Chronicle $chronicle) : Response
  {
    return $this->render('chronicle/show.html.twig', [
      'chronicle' => $chronicle,
      'setting' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/homebrew", name: "homebrew_index", methods: ["GET"])]
  public function homebrew(Chronicle $chronicle) : Response
  {
    return $this->render('chronicle/homebrew/index.html.twig', [
      'chronicle' => $chronicle,
      'setting' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/party", name: "chronicle_party_index", methods: ["GET"])]
  public function party(Chronicle $chronicle) : Response
  {
    return $this->render('chronicle/party/index.html.twig', [
      'chronicle' => $chronicle,
      'setting' => $chronicle->getType(),
    ]);
  }

  #[Route("/{id<\d+>}/npc", name: "chronicle_npc_index", methods: ["GET"])]
  public function npc(Request $request, Chronicle $chronicle) : Response
  {
    return $this->render('character/npc/index.html.twig', [
      'chronicle' => $chronicle,
      'setting' => $chronicle->getType(),
      'back' => ['path' => 'chronicle_show', 'id' => $chronicle->getId()],
    ]);
  }

  #[Route("/{id<\d+>}/npc/add", name: "chronicle_npc_add", methods: ["GET"])]
  public function addNpc(Chronicle $chronicle) : Response
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

    return $this->render('chronicle/party/playerChange.html.twig', [
      'chronicle' => $chronicle,
      'action' => 'add',
      'setting' => $chronicle->getType(),
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/party/remove/player", name: "chronicle_remove_player")]
  public function removePlayer(Request $request, Chronicle $chronicle) : Response
  {
    $availablePlayers = $chronicle->getPlayers();
    if (count($availablePlayers) > 0) {
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

    return $this->render('chronicle/party/playerChange.html.twig', [
      'chronicle' => $chronicle,
      'action' => 'remove',
      'setting' => $chronicle->getType(),
      'form' => $form,
    ]);
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
    return $this->render('notes/category.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/note/category/{category<\d+>}/edit', name: 'chronicle_note_category_edit', methods: ['GET', 'POST'])]
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
    return $this->render('notes/category.html.twig', [
      'category' => $category,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/note/category/{category<\d+>}/delete', name: 'chronicle_note_category_delete', methods: ['GET', 'DELETE'])]
  public function deleteCategory(Chronicle $chronicle, NoteCategory $category): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $this->dataService->remove($category);

    return $this->redirectToRoute('chronicle_notes', ['id' => $chronicle->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/infos', name: 'chronicle_infos_index', methods: ['GET'])]
  public function infos(Request $request, Chronicle $chronicle)
  {
    return $this->render('chronicle/infos/index.html.twig', [
      'character' => $chronicle->getCharacter($this->getUser()),
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  #[Route('/{id<\d+>}/rules/{type<\w+>}', name: 'chronicle_rules_set', methods: ['GET', 'POST'])]
  public function setRules(Request $request, Chronicle $chronicle, string $type)
  {
    // Security, only the storyteller can change these settings, but everyone can see it
    $user = $this->getUser();
    $disabled = true;
    if ($chronicle->getStoryteller() === $user || in_array('ROLE_GM', $user->getRoles())) {
      $disabled = false;
    }

    switch ($type) {
      case 'vampire':
        $vService = new VampireService($this->dataService);
        $form = $this->createForm(VampireRulesType::class, null, ['ruleset' => $vService->getRules($chronicle), 'disabled' => $disabled]);
        break;

      default:
        break;
    }

    // We don't have a form, that mean there are no rules for this template, redirect
    if (!isset($form)) {
      return $this->redirectToRoute('homebrew_index', ['id' => $chronicle->getId()]);
    }

    if (!$disabled) {
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $chronicle->setRules($form->getData(), 'vampire');
        $this->dataService->save($chronicle);
  
        return $this->redirectToRoute('homebrew_index', ['id' => $chronicle->getId()]);
      }
    }

    return $this->render('chronicle/homebrew/rules.html.twig', [
      'type' => $type,
      'disabled' => $disabled,
      'chronicle' => $chronicle,
      'form' => $form,
    ]);
  }
}