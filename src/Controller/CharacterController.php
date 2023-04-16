<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\CharacterNote;
use App\Entity\Chronicle;
use App\Entity\Human;
use App\Entity\Roll;
use App\Entity\Vampire;
use App\Form\CharacterNoteType;
use App\Form\CharacterType;
use App\Form\VampireType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\VampireService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Psr\Log\LoggerInterface;
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
  private VampireService $vService;
  /** @var array<string, array<string, array<string, string|null>>> */
  private array $attributes;
  /** @var array<string, array<string, string|null>> */
  private array $skills;

  public function __construct(DataService $dataService, CreationService $create, CharacterService $service, VampireService $vService)
  {
    $this->dataService = $dataService;
    $this->create = $create;
    $this->service = $service;
    $this->vService = $vService;
    $this->attributes = $this->service->getSortedAttributes();
    $this->skills = $this->service->getSortedSkills();
  }

  #[Route('/', name: 'character_index', methods: ['GET'])]
  public function index(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy(
        [
          'player' => $user->getId(),
          'isNpc' => false,
          'isTemplate' => false,
        ],
        [
          'chronicle' => 'DESC',
          // 'type' => 'ASC',
          'firstName' => 'ASC',
          'lastName' => 'ASC',
        ]),
      'npc' => $characterRepository->findBy(
        [
          'player' => $user->getId(),
          'isNpc' => true
        ],
        [
          'chronicle' => 'DESC',
          // 'type' => 'ASC',
          'firstName' => 'ASC',
          'lastName' => 'ASC',
        ]
      ),
    ]);
  }
  
  #[Route('/generated', name: 'character_generated_index', methods: ['GET'])]
  public function indexTemplates(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'generated' => $characterRepository->findBy([
        'isTemplate' => true,
      ],
      [
        'chronicle' => 'ASC',
        // 'type' => 'ASC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]),
    ]);
  }

  #[Route('/npc', name: 'character_npc_index', methods: ['GET'])]
  public function indexNpc(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'npc' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ],
      [
        'chronicle' => 'ASC',
        // 'type' => 'ASC',
        'firstName' => 'ASC',
        'lastName' => 'ASC',
      ]),
    ]);
  }

  #[Route('/new/{isNpc<\d+>}/{chronicle<\d+>}/', name: 'character_new', methods: ['GET', 'POST'], defaults: ['isNpc' => 0, 'chronicle' => 0])]
  public function new(Request $request, EntityManagerInterface $entityManager, Chronicle $chronicle = null, bool $isNpc = false): Response
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

  #[Route('/new/template/', name: 'character_new_template', methods: ['GET', 'POST'])]
  public function newTemplate(Request $request, EntityManagerInterface $entityManager): Response
  {
    $character = new Human();

    $character->setIsNpc(false);
    $character->setIsTemplate(true);

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
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }
    $this->dataService->loadMeritsPrerequisites($character->getMerits(), 'character');

    $rolls = $this->dataService->findBy(Roll::class, ['isImportant' => "true"], ['name' => 'ASC']);

    return $this->render('character_sheet/'.$character->getType().'/show.html.twig', [
      'character' => $character,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'rolls' => $rolls,
      'setting' => $character->getType(),
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

    $merits = $this->service->filterMerits($character, false);
    $setting = $character->getType();

    switch ($setting) {
      case 'vampire':
        $form = $formFactory->createNamed('character', VampireType::class, $character, ['is_edit' => true]);
        break;

      default:
        $form = $this->createForm(CharacterType::class, $character, ['is_edit' => true]);
        break;
    }
    $form->handleRequest($request);
    $extraData = $form->getExtraData();

    if ($form->isSubmitted() && $form->isValid()) {
      if (is_null($character->getLookAge()) && !is_null($character->getAge())) {
        $character->setLookAge($character->getAge());
      }
      if (isset($extraData['merits'])) {
        $this->create->addMerits($character, $extraData['merits']);
      }
      if (isset($extraData['meritsUp'])) {
        $this->create->updateMerits($extraData['meritsUp']);
      }
      if (isset($extraData['specialties'])) {
        $this->create->addSpecialties($character, $extraData['specialties']);
      }
      if (isset($extraData['willpower'])) {
        $this->service->updateWillpower($character, $extraData['willpower']);
      }
      if ($character->getType() == "vampire") {
        /** @var Vampire $character */
        $this->vService->handleEdit($character, $extraData);
      }
      if (isset($extraData['xp']) && !isset($extraData['isFree'])) {
        $character->spendXp($extraData['xp']['spend']);
        $isFree = false;
      } else {
        $isFree = true;
      }
      $this->service->updateLogs($character, $extraData['xpLogs'], $isFree);
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    $this->dataService->loadMeritsPrerequisites($character->getMerits(), 'character');
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/'.$setting.'/edit.html.twig', [
      'character' => $character,
      'setting' => $setting,
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'merits' => $merits,
      //should send back the data of the custom form, when the form was submitted but not validated, no hurry at all though
      $setting => $this->service->getSpecial($character),
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
    ->add('submit', SubmitType::class,['label' => 'action.duplicate'])
    ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
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


  #[Route('/{id<\d+>}/background', name: 'character_background', methods: ['GET', 'POST'])]
  public function background(Request $request, Character $character): Response
  {
    $converter = new LeagueMarkdown();
    $form = $this->createFormBuilder()
      ->add('background', CKEditorType::class , [
        'empty_data' => '', 
        'data' => $converter->convert($character->getBackground()), 
        'label' => 'background.label', 
        'translation_domain' => 'character'
      ])
      ->add('save', SubmitType::class, [
        'label' => 'save', 
        'translation_domain' => 'app'
      ])
      ->getForm();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $background = $form->get('background')->getData();
      if ($background == null) {
        $background = "";
      }
      $character->setBackground($background);
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character_sheet/edit/background.html.twig', [
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
  public function EditNote(Request $request, Character $character, CharacterNote $note): Response
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
  public function updateTrait(Request $request, Character $character) : JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateTrait($character, $data);
      return new JsonResponse('ok');
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{id<\d+>}/experience/update', name: 'character_experience_update', methods: ['POST'])]
  public function updateExperience(Request $request, Character $character) : JsonResponse|RedirectResponse
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
  public function updateAvatar(Request $request, Character $character) : JsonResponse|RedirectResponse
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
