<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Character;
use App\Entity\CharacterAccess;
use App\Entity\CharacterNote;
use App\Entity\Chronicle;
use App\Entity\Derangement;
use App\Entity\Human;
use App\Entity\Roll;
use App\Form\CharacterAccessType;
use App\Form\CharacterInfoAccessType;
use App\Form\CharacterNoteType;
use App\Form\CharacterType;
use App\Form\VampireType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\LeagueMarkdown;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/character')]
class CharacterController extends AbstractController
{
  private DataService $dataService;
  private CreationService $create;
  private CharacterService $service;
  /** @var array<string, array<string, array<string, string|null>>> */
  private array $attributes;
  /** @var array<string, array<string, string|null>> */
  private array $skills;

  public function __construct(DataService $dataService, CreationService $create, CharacterService $service)
  {
    $this->dataService = $dataService;
    $this->create = $create;
    $this->service = $service;
    $this->attributes = $this->service->getSortedAttributes();
    $this->skills = $this->service->getSortedSkills();
  }

  #[Route('', name: 'character_index', methods: ['GET'])]
  public function index(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $characters = $characterRepository->findBy(
      [
        'player' => $user->getId(),
        'isNpc' => false,
        'isPremade' => false,
      ],
      [
        'chronicle' => 'DESC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]
    );
    $characters = $this->service->sortCharacters(...$characters);
    $npc = $characterRepository->findBy(
      [
        'player' => $user->getId(),
        'isNpc' => true
      ],
      [
        'chronicle' => 'ASC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]
    );
    $npc = $this->service->sortCharacters(...$npc);
    return $this->render('character/index.html.twig', [
      'characters' => $characters,
      'npc' => $npc,
    ]);
  }

  #[Route('/premades', name: 'character_premade_index', methods: ['GET'])]
  public function indexPremades(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();

    $characters = $characterRepository->findBy(
      [
        'isPremade' => true,
      ],
      [
        'chronicle' => 'ASC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]
    );
    $characters = $this->service->sortCharacters(...$characters);

    return $this->render('character/index.html.twig', [
      'premade' => $characters,
    ]);
  }

  #[Route('/npc', name: 'character_npc_index', methods: ['GET'])]
  public function indexNpc(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $characters = $characterRepository->findBy(
      [
        'player' => $user->getId(),
        'isNpc' => true
      ],
      [
        'chronicle' => 'ASC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]
    );
    $characters = $this->service->sortCharacters(...$characters);
    return $this->render('character/index.html.twig', [
      'npc' => $characters,
    ]);
  }

  #[Route('/new/{isNpc<\d+>}/{chronicle<\d+>}', name: 'character_new', methods: ['GET', 'POST'], defaults: ['isNpc' => 0, 'chronicle' => 0])]
  public function new(Request $request, Chronicle $chronicle = null, bool $isNpc = false): Response
  {
    $character = new Human();
    $character->setChronicle($chronicle);
    $character->setIsNpc($isNpc);
    /** @var User $user */
    $user = $this->getUser();
    $character->setPlayer($user);
    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterType::class, $character);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->create->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->create->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->getResolve() + $character->getAttributes()->getComposure());

      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'merits' => $merits,
    ]);
  }

  #[Route('/new/premade', name: 'character_new_premade', methods: ['GET', 'POST'])]
  public function newTemplate(Request $request, EntityManagerInterface $entityManager): Response
  {
    $character = new Human();

    $character->setIsNpc(false);
    $character->setIsPremade(true);

    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterType::class, $character);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->create->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->create->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->getResolve() + $character->getAttributes()->getComposure());
      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'merits' => $merits,
    ]);
  }

  #[Route('/{id<\d+>}', name: 'character_show', methods: ['GET'])]
  public function show(Character $character): Response
  {
    if ($character->getPlayer() != $this->getUser() && ($character->getChronicle() && $character->getChronicle()->getStoryteller() != $this->getUser())) {
      // TODO => SHOULD ADD A CHECK FOR WHEN THE PLAYER HAS NO CHARACTER IN THE CHRONICLE FOR TOTAL DENIAL OF ACCESS
      // $this->addFlash('notice', 'You are not allowed to see this character');
      // return $this->redirectToRoute('character_index');
      return $this->redirectToRoute('character_peek', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    $this->dataService->loadMeritsPrerequisites($character->getMerits(), 'character');
    $derangements = $this->dataService->findBy(Derangement::class, ['type' => [$character->getType(), null]], ['name' => 'ASC']);
    $rolls = $this->dataService->findBy(Roll::class, ['isImportant' => "true"], ['name' => 'ASC']);

    $removables = [
      'attribute',
      'skill',
      'merit',
      'specialty',
      'willpower',
      'derangement',
    ];

    return $this->render('character_sheet/' . $character->getType() . '/show.html.twig', [
      'character' => $character,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'rolls' => $rolls,
      'setting' => $character->getSetting(),
      'removables' => $removables,
      'derangements' => $derangements,
    ]);
  }

  #[Route('/{id<\d+>}/edit', name: 'character_edit', methods: ['GET', 'POST'])]
  public function edit(FormFactoryInterface $formFactory, Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($character->getPlayer() != $this->getUser() && ($character->getChronicle() && $character->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }

    switch ($character->getType()) {
      case 'vampire':
        $form = $formFactory->createNamed('character', VampireType::class, $character, ['is_edit' => true]);
        break;

      default:
        $form = $this->createForm(CharacterType::class, $character, ['is_edit' => true]);
        break;
    }
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->service->editCharacter($character, $form->getExtraData());

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    $merits = $this->service->loadMerits($character, false);

    return $this->render('character_sheet/' . $character->getType() . '/edit.html.twig', [
      'character' => $character,
      'setting' => $character->getSetting(),
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'merits' => $merits,
      //should send back the data of the custom form, when the form was submitted but not validated, no hurry at all though
      $character->getType() => $this->service->getSpecial($character),
    ]);
  }

  #[Route('/{id<\d+>}/delete', name: 'character_delete', methods: ['GET'])]
  public function delete(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('delete', $character);
    // if ($this->isCsrfTokenValid('delete' . $character->getId(), $request->request->get('_token'))) {
    $this->dataService->remove($character);
    // }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/peek', name: 'character_peek', methods: ['GET', 'POST'])]
  public function peek(Character $character): Response
  {
    $peeker = $character->getChronicle()->getCharacter($this->getUser());
    if ($peeker === $character) {

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    $access = $peeker->getSpecificPeekingRights($character);

    return $this->render('character_sheet/' . $character->getType() . '/peek.html.twig', [
      'peeker' => $peeker,
      'access' => $access,
      'character' => $character,
      'setting' => $character->getSetting()
    ]);
  }

  #[Route('/{id<\d+>}/duplicate', name: 'character_duplicate', methods: ['GET', 'POST'])]
  public function duplicate(Request $request, Character $character): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $stories = $user->getStories();

    $form = $this->createFormBuilder(null, ['translation_domain' => 'app'])
      ->add('story', ChoiceType::class, [
        'choices' => $stories,
        'choice_label' => 'name',
        'choice_value' => 'id',
      ])
      ->add('submit', SubmitType::class, ['label' => 'action.duplicate'])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // Need to check for the path
      $path = $this->getParameter('characters_directory');
      if (is_string($path)) {
        $this->dataService->duplicateCharacter($character, $form->get('story')->getData(), $user, $path);
        return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
      }
    }
    return $this->render('character/duplicate.html.twig', [
      'form' => $form,
      'character' => $character,
    ]);
  }

  #[Route('/{id<\d+>}/lesser/add', name: 'character_lesser_add', methods: ['GET', 'POST'])]
  public function applyLesserTemplate(Request $request, Character $character): Response
  {
    $oldTemplate = $character->getLesserTemplate();
    $templates = $this->service->getAllAvailableLesserTemplates($oldTemplate);
    $form = $this->createFormBuilder(null, ['translation_domain' => 'app'])
      ->add('template', ChoiceType::class, [
        'choices' => $templates,
      ])
      ->add('submit', SubmitType::class, ['label' => 'Apply'])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (!is_null($oldTemplate)) {
        if ($oldTemplate->getType() == $form->getData()['template']) {
          // No change in template, shouldn't happen, return
          return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
        }
        // We deactivate the old template
        $oldTemplate->setIsActive(false);
      }
      
      return $this->redirectToRoute('character_ghoulify', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character/lesser/add.html.twig', [
      'form' => $form,
      'character' => $character,
    ]);
  }

  #[Route('/{id<\d+>}/lesser/remove', name: 'character_lesser_remove', methods: ['GET'])]
  public function removeLesserTemplate(Character $character): Response
  {
    $character->getLesserTemplate()->setIsActive(false);
    $this->dataService->save($character);

    return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/morality/increase', name: 'character_morality_increase', methods: ['POST'])]
  public function moralityIncrease(Request $request, Character $character): Response
  {
    $this->service->moralityIncrease($character, (bool)$request->request->get('derangement'), (bool)$request->request->get('free'));

    return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/morality/decrease', name: 'character_morality_decrease', methods: ['POST'])]
  public function moralityDecrease(Request $request, Character $character): Response
  {
    $this->service->moralityDecrease($character, (int)$request->request->get('derangement'), $request->request->get('details'));

    return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/derangement/new', name: 'character_derangement_new', methods: ['POST'])]
  public function derangementNew(Request $request, Character $character): Response
  {
    $this->service->newCharacterDerangement($character, (int)$request->request->get('derangement'), $request->request->get('details'));

    return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/ability/remove', name: 'character_ability_removal', methods: ['POST'])]
  public function abilityRemoval(Request $request, Character $character): Response
  {
    // dd($character, $data->get('type'), $data->get('element'), $data->get('method'));
    if ($this->service->removeAbility($character, $request->request->all())) {
      $this->addFlash("info", "{$request->request->get('element')}: {$request->request->get('method')}");
    }
    return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{id<\d+>}/access/add', name: 'character_access_add', methods: ['GET', 'POST'])]
  public function addAccess(Request $request, Character $character): Response
  {
    $access = new CharacterAccess();

    $access->setTarget($character);
    $form = $this->createForm(CharacterAccessType::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($access);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/edit/access.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/access/{accessor<\d+>}', name: 'character_access_edit', methods: ['GET', 'POST'])]
  public function editAccess(Request $request, Character $character, Character $accessor): Response
  {
    $access = $this->dataService->findOneBy(CharacterAccess::class, ['target' => $character, 'accessor' => $accessor]);

    $form = $this->createForm(CharacterAccessType::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/edit/access.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/infos/basic/{type}', name: 'character_basic_infos_edit', methods: ['GET', 'POST'])]
  public function basicInfosEdit(Request $request, Character $character, string $type): Response
  {
    $name = ucfirst($type);
    $get = "get".$name;
    $set = "set".$name;

    $converter = new LeagueMarkdown();
    $form = $this->createFormBuilder()
      ->add($type, CKEditorType::class, [
        'empty_data' => '',
        'data' => $converter->convert($character->$get()),
        'label' => "$type.label",
        'translation_domain' => 'character'
      ])
      ->add('save', SubmitType::class, [
        'label' => 'save',
        'translation_domain' => 'app'
      ])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->get($type)->getData();
      if ($data == null) {
        $data = "";
      }
      $character->$set($data);
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/edit/basic_infos.html.twig', [
      'character' => $character,
      'form' => $form,
      'type' => $type,
    ]);
  }

  #[Route('/{id<\d+>}/infos/edit', name: 'character_infos_edit', methods: ['GET', 'POST'])]
  public function editInfos(Request $request, Character $character): Response
  {
    $form = $this->createForm(CharacterInfoAccessType::class, $character, ['path' => $this->getParameter('characters_direct_directory'),]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/edit/infos.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/note/new', name: 'character_note_new', methods: ['GET', 'POST'])]
  public function addNote(Request $request, Character $character): Response
  {
    $note = new CharacterNote();
    /** @var User $user */
    $user = $this->getUser();
    $note->setAuthor($user);
    $note->setCharacter($character);

    $latestNote = $character->getNotes()->first();
    $options = [];
    if ($latestNote instanceof CharacterNote && $latestNote->getAssignedAt() instanceof DateTimeImmutable) {
      $options['date'] = $latestNote->getAssignedAt()->format('Y-m-d H:i:s');
    }
    $form = $this->createForm(CharacterNoteType::class, $note, $options);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $character->addNote($note);
      $this->dataService->save($note);

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'notes'], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/notes/new.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/notes/{note}/edit', name: 'character_note_edit', methods: ['GET', 'POST'])]
  public function editNote(Request $request, Character $character, CharacterNote $note): Response
  {
    $options = [];
    $form = $this->createForm(CharacterNoteType::class, $note, $options);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => "notes"], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/notes/edit.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/wounds/update', name: 'character_wounds_update', methods: ['POST'])]
  public function updateWounds(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      if ($data->action == "take") {
        $this->service->takeWound($character, $data->value);
      } else {
        $this->service->healWound($character, $data->value);
      }

      return new JsonResponse($data);
    } else {

      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{id<\d+>}/trait/update', name: 'character_trait_update', methods: ['POST'])]
  public function updateTrait(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateTrait($character, $data);
      return new JsonResponse('ok');
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{id<\d+>}/lesser/trait/update', name: 'character_lesser_trait_update', methods: ['POST'])]
  public function updateLesserTrait(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateLesserTrait($character, $data);
      return new JsonResponse('ok');
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{id<\d+>}/experience/update', name: 'character_experience_update', methods: ['POST'])]
  public function updateExperience(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateExperience($character, $data);
      return new JsonResponse(['used' => $character->getXpUsed(), 'total' => $character->getXpTotal()]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{id<\d+>}/avatar/update', name: 'character_avatar_update', methods: ['POST'])]
  public function updateAvatar(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $avatar = $request->files->get('avatar')['upload'];
      $path = $this->getParameter('characters_directory');
      if (!is_null($avatar) && is_string($path)) {
        $file = $this->dataService->upload($avatar, $path, (string)$character->getId());

        if (!is_null($file)) {
          return new JsonResponse($file);
        }
      }

      return new JsonResponse(null, 204);
    } else {

      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}
