<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Character;
use App\Entity\CharacterAccess;
use App\Entity\CharacterNote;
use App\Entity\Chronicle;
use App\Entity\ContentType;
use App\Entity\Derangement;
use App\Entity\Human;
use App\Entity\Item;
use App\Entity\Possessed;
use App\Entity\Roll;
use App\Entity\StatusEffect;
use App\Form\CharacterAccessForm;
use App\Form\CharacterInfoAccessForm;
use App\Form\CharacterNoteForm;
use App\Form\CharacterForm;
use App\Form\Type\RichTextEditorForm;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\ItemService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;
use Symfony\UX\Dropzone\Form\DropzoneType;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/character')]
class CharacterController extends AbstractController
{
  private DataService $dataService;
  private CreationService $creationService;
  private CharacterService $service;
  private ItemService $itemService;
  /** @var array<string, array<string, array<string, string|null>>> */
  private array $attributes;
  /** @var array<string, array<string, string|null>> */
  private array $skills;
  /** @var array<string, array<string, string|null>> */
  private array $ancientSkills;

  public function __construct(DataService $dataService, CreationService $creationService, CharacterService $service, ItemService $itemService)
  {
    $this->dataService = $dataService;
    $this->creationService = $creationService;
    $this->service = $service;
    $this->itemService = $itemService;
    $this->attributes = $this->service->getSortedAttributes();
    $this->skills = $this->service->getSortedSkills();
    $this->ancientSkills = $this->service->getSortedAncientSkills();
  }

  #[Route('s', name: 'character_index', methods: ['GET'])]
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

  #[Route('s/premades', name: 'character_premade_index', methods: ['GET'])]
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

  #[Route('s/npc', name: 'character_npc_index', methods: ['GET'])]
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

  #[Route("s/list/{filter<\w+>}/{id<\w+>}", name: "character_list", methods: ["GET"])]
  public function list(?string $filter = null, ?int $id = null) : Response
  {
    $characters = $this->dataService->getList($filter, $id, Character::class, "getCharacters");
    $characters = $this->service->sortCharacters(...$characters);;
    return $this->render('character/index.html.twig', [
      'characters' => $characters,
    ]);
  }

  #[Route('/{id<\d+>}', name: 'character_show', methods: ['GET'])]
  public function show(Request $request, Character $character, FormFactoryInterface $formFactory): Response
  {
    $user = $this->getUser();
    if ($character->getPlayer() != $user && ($character->getChronicle() && $character->getChronicle()->getStoryteller() != $user)) {
      if ($user instanceof User && $user->getRole() != 'ROLE_GM')
        return $this->redirectToRoute('character_peek', ['id' => $character->getId()]);
    }
    switch ($character->getType()) {
      case 'possessed':
        if (($template = $character->getLesserTemplate()) instanceof Possessed && $template->getVices()->isEmpty()) {
          // We need to set the template
          return $this->redirectToRoute('possessed_setup', ['id' => $character->getLesserTemplate()->getId()]);
        }
    }
    $this->dataService->loadMeritsPrerequisites($character->getMerits());
    $type = $this->dataService->findOneBy(ContentType::class, ['name' => $character->getType()]);
    $derangements = $this->dataService->findBy(Derangement::class, ['type' => [$type->getid(), null]], ['name' => 'ASC']);
    $rolls = $this->dataService->findBy(Roll::class, ['isImportant' => "true"], ['name' => 'ASC']);

    $removables = $this->service->getRemovableAttributes($character);
    $statusList = $this->service->getStatusType($character);
    
    $avatarForm = $formFactory->createNamedBuilder("avatar", FormType::class, null, [
      'translation_domain' => 'character',
      'attr' => [
        'name' => 'avatar',
        'data-action' => 'character--avatar#update',
        'data-character--avatar-target' => 'form',
        'method' => 'post',
      ],
    ])
      ->add('upload', DropzoneType::class, [
        'label' => "false",
        'attr' => ['placeholder' => 'avatar.upload'],
      ])
      ->getForm();

    $avatarForm->handleRequest($request);

    return $this->render("character_sheet_type/show.html.twig", [
      'character' => $character,
      'attributes' => $this->attributes,
      'skills' => $this->getSkillList($character),
      'rolls' => $rolls,
      'setting' => $character->getSetting(),
      'special' => $this->service->getSpecific($character, $type),
      'removables' => $removables,
      'statusList' => $statusList,
      'derangements' => $derangements,
      'avatarForm' => $avatarForm->createView(),
    ]);
  }

  #[Route('/new/{isAncient<\d+>?0}/{isNpc<\d+>?0}/{chronicle<\d+>?0}', name: 'character_new', methods: ['GET', 'POST'])]
  public function new(Request $request, bool $isAncient, ?Chronicle $chronicle = null, bool $isNpc = false): Response
  {
    $character = new Human($isAncient);
    $character->setChronicle($chronicle);
    $character->setIsNpc($isNpc);

    /** @var User $user */
    $user = $this->getUser();
    $character->setPlayer($user);
    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterForm::class, $character, ['user' => $this->getUser()]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->creationService->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->get('resolve', false) + $character->getAttributes()->get('composure', false));

      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->getskillList($character),
      'merits' => $merits,
    ]);
  }

  #[Route('/premade/new/{isAncient<\d+>}', name: 'character_new_premade', methods: ['GET', 'POST'])]
  public function newTemplate(Request $request, bool $isAncient = false): Response
  {
    $character = new Human($isAncient);
    $this->denyAccessUnlessGranted('ROLE_GM');
    
    $character->setIsNpc(false);
    $character->setIsPremade(true);

    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterForm::class, $character);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->creationService->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->get('resolve', false) + $character->getAttributes()->get('composure', false));
      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }
    $this->dataService->loadMeritsPrerequisites($merits);

    return $this->render('character_sheet/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->getSkillList($character),
      'merits' => $merits,
    ]);
  }

  #[Route('/{id<\d+>}/edit', name: 'character_edit', methods: ['GET', 'POST'])]
  #[IsGranted('edit', 'character')]
  public function edit(FormFactoryInterface $formFactory, Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);
    $form = $formFactory->createNamed('character_form', $character->getForm(), $character, ['is_edit' => true, 'user' => $this->getUser()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $spent = $this->service->editCharacter($character, $form->getExtraData());
      $this->addFlash('success', ["character.edit.success", ['%count%' => $spent]]);
      return $this->redirectToRoute('character_show', ['id' => $character->getId(), 303]);
    }

    $merits = $this->service->loadMerits($character, false);

    return $this->render("character_sheet_type/edit.html.twig", [
      'character' => $character,
      'setting' => $character->getSetting(),
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->getSkillList($character),
      'merits' => $merits,
      //should send back the data of the custom form, when the form was submitted but not validated, no hurry at all though
      $character->getType() => $this->service->getSpecial($character),
    ]);
  }

  #[Route('/{id<\d+>}/delete', name: 'character_delete', methods: ['GET', 'POST'])]
  public function delete(Character $character, Request $request): Response
  {
    $this->denyAccessUnlessGranted('delete', $character);

    $form = $this->createFormBuilder()
      ->add('confirm', CheckboxType::class, [
        'label' => "delete.label",
        'help' => "delete.help",
        'translation_domain' => "character",
      ])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $chronicle = $character->getChronicle();
      try {
        $this->dataService->remove($character);
        $this->addFlash('success', ["character.delete.success", ['%name%' => $character->getName()]]);
        if ($chronicle && $character->isNpc()) {
          return $this->render('character/npc/index.html.twig', [
            'chronicle' => $chronicle,
            'setting' => $chronicle->getType(),
            'back' => ['path' => 'chronicle_show', 'params' => ['id' => $chronicle->getId()]],
          ]);
        } else {
          return $this->redirectToRoute('character_index');
        }
      } catch (\Throwable $th) {
        $this->addFlash('error', ["character.delete.error", ['%name%' => $character->getName()]]);
      }

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    return $this->render("character/delete.html.twig", [
      'form' => $form,
      'action' => 'delete',
      'character' => $character,
      'setting' => $character->getSetting(),
    ]);
  }

  #[Route('/{id<\d+>}/peek/{peeker}', name: 'character_peek', methods: ['GET'])]
  public function peek(Request $request, Character $character, ?Character $peeker): Response
  {
    if ($request->isXmlHttpRequest()) {
      if ($peeker instanceof Character) {
        $access = $peeker->getSpecificPeekingRights($character);

        return $this->render("character_sheet/peek/_base.html.twig", [
          'peeker' => $peeker,
          'access' => $access,
          'character' => $character,
          'setting' => $character->getChronicle()->getType(),
        ]);
      }
    } else {

      if (!$peeker instanceof Character) {
        $peeker = $character->getChronicle()->getCharacter($this->getUser());
      }
      return $this->peekAs($character, $peeker);
    }
  
    $this->addFlash('notice', 'character.peek.declined');
    if ($request->headers->get('referer')) {
      return $this->redirect($request->headers->get('referer'));
    }

    return $this->redirectToRoute('character_index');
  }

  public function peekAs(Character $character, Character $peeker): Response
  {
    if (is_null($character->getChronicle())) {
      $this->addFlash('warning', 'character.peek.unavailable');

      return $this->redirectToRoute('character_index');
    }

    if ($peeker === $character) {
      $this->addFlash('notice', 'character.peek.self');
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    } else if (is_null($peeker) || is_null($peeker->getSpecificPeekingRights($character)) || empty($peeker->getSpecificPeekingRights($character)->getRights())) {
      
      $this->addFlash('notice', 'character.peek.declined');
      return $this->redirectToRoute('index');
    }
    $access = $peeker->getSpecificPeekingRights($character);

    return $this->render("character_sheet/peek.html.twig", [
      'peeker' => $peeker,
      'access' => $access,
      'character' => $character,
      'setting' => $character->getChronicle()->getType(),
    ]);
  }

  #[Route('/{id<\d+>}/duplicate', name: 'character_duplicate', methods: ['GET', 'POST'])]
  public function duplicate(Request $request, Character $character): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    $stories = $user->getStories();
    $chronicles = $user->getChronicles();
    foreach ($chronicles as $chronicle) {
      /** @var Chronicle $chronicle */
      if (is_null($chronicle->getCharacter($this->getUser()))) {
        $stories[] = $chronicle;
      }
    }

    $form = $this->createFormBuilder(null, ['translation_domain' => 'app'])
      ->add('story', ChoiceType::class, [
        'required' => false,
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
        $chronicle = $form->get('story')->getData();
        $newCharacter = $this->dataService->duplicateCharacter($character, $chronicle, $user, $path);
        if ($newCharacter) {
          // Success
          if ($chronicle) {
            $this->addFlash('success', ['character.duplicate.success.chronicle', ['name' => $newCharacter, 'chronicle' => $chronicle]]);
          } else {
            $this->addFlash('success', ['character.duplicate.success.list', ['name' => $newCharacter]]);
          }
          return $this->redirectToRoute('character_show', ['id' => $newCharacter->getId()]);
        } else {
          // Failed
          $this->addFlash('warning', ['character.duplicate.failed', ['name' => $character]]);
        }
      }
    }
    return $this->render('character/duplicate.html.twig', [
      'form' => $form,
      'character' => $character,
    ]);
  }

  #[Route('/{id<\d+>}/access/add', name: 'character_access_add', methods: ['GET', 'POST'])]
  public function addAccess(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $access = new CharacterAccess();

    $access->setTarget($character);
    try {
      $form = $this->createForm(CharacterAccessForm::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
    } catch (\Throwable $th) {
      if ($th->getCode() == 847) {
        $this->addFlash('warning', ["character.access.none", []]);
        return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'informations']);
      }
    }
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($access);

      $this->addFlash('success', ["character.access.new", ['name' => $access->getAccessor()->getName()]]);
      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'informations']);
    }
    return $this->render('character_sheet/elements/access.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/access/{accessor<\d+>}', name: 'character_access_edit', methods: ['GET', 'POST'])]
  public function editAccess(Request $request, Character $character, Character $accessor): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $access = $this->dataService->findOneBy(CharacterAccess::class, ['target' => $character, 'accessor' => $accessor]);

    $form = $this->createForm(CharacterAccessForm::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      $this->addFlash('success', ["character.access.edit", ['name' => $accessor->getName()]]);
      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'informations']);
    }
    return $this->render('character_sheet/elements/access.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/infos/basic/{type}', name: 'character_basic_infos_edit', methods: ['GET', 'POST'])]
  public function basicInfosEdit(Request $request, Character $character, string $type): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $name = ucfirst($type);
    $get = "get".$name;
    $set = "set".$name;

    $form = $this->createFormBuilder()
      ->add($type, RichTextEditorForm::class, [
        'empty_data' => '',
        'data' => $character->$get(),
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

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => $type]);
    }
    return $this->render('character_sheet/infos/basic.html.twig', [
      'character' => $character,
      'form' => $form,
      'type' => $type,
    ]);
  }

  #[Route('/{id<\d+>}/infos/edit', name: 'character_infos_edit', methods: ['GET', 'POST'])]
  public function editInfos(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $form = $this->createForm(CharacterInfoAccessForm::class, $character, ['path' => $this->getParameter('characters_direct_directory'),]);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'informations']);
    }
    return $this->render('character_sheet/infos/edit.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  // Fetch the list of items accessible for the character
  #[Route('/{id<\d+>}/item/list', name: 'character_item_list')]
  public function listItem(Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $templates = $this->dataService->findBy(Item::class, ['owner' => null, 'homebrewFor' => [null]], ['name' => 'ASC']);

    return $this->render('character_sheet/items/add/elements.html.twig', [
      'character' => $character,
      'templates' => $templates,
    ]);
  }

  // Fetch the list of relations for the character
  #[Route('/{id<\d+>}/relations', name: 'character_relations')]
  public function listRelations(Request $request, Character $character): Response
  {
    if ($request->isXmlHttpRequest()) {

    // $template = "show";
      $template = "_show";
      
      return $this->render("chronicle/infos/_characters.html.twig", [
        'character' => $character,
      ]);
    }
    return $this->render("index.html.twig");
  }

  #[Route('/{character<\d+>}/item/container/add', name: 'character_container_add', methods: ['GET', 'POST'])]
  public function addContainer(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $item = new Item();
    $item->setIsContainer(true);
    $item->setOwner($character);

    $form = $this->createForm($item->getForm(), $item);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->itemService->save($form->getData(), $form->get('img')->getData());

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'inventory']);
    }

    return $this->render('character_sheet/items/add.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/{character<\d+>}/item/new', name: 'character_item_new', methods: ['GET', 'POST'])]
  public function newItem(Request $request, Character $character): Response
  {
    $types = $this->itemService->getTypes();

    $forms = [];
    foreach ($types as $type) {
      $item = new $type[0]();
      $item->setOwner($character);
      $forms[] = $this->createForm($type[1], $item);
    }
    if ($request->isMethod('POST')) {
      foreach ($forms as $form) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $form->getData()->setOwner($character);
          $this->itemService->save($form->getData(), $form->get('img')->getData());
        }
      }

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'inventory']);
    }

    $formViews = [];
    foreach ($forms as $form) {
      $formViews[] = $form->createView();
    }
    return $this->render('item/new.html.twig', [
      'items' => $types,
      'forms' => $formViews,
    ]);
  }

  #[Route('/{character<\d+>}/item/{template<\d+>}/add', name: 'character_item_add', methods: ['GET', 'POST'])]
  public function addItem(Request $request, Character $character, Item $template): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $item = clone($template);
    $character->addItem($item);
    $form = $this->createForm($item->getForm(), $item);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($item);

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'inventory']);
    }

    return $this->render('character_sheet/items/add.html.twig', [
      'form' => $form,
    ]);
  }

  // Fetch the list of relations for the character
  #[Route('/{id<\d+>}/notes', name: 'character_notes')]
  public function listNotes(Request $request, Character $character): Response
  {
    if ($request->isXmlHttpRequest()) {

    // $template = "show";
      $template = "_show";
      
      return $this->render("character_sheet/elements/notes.html.twig", [
        'character' => $character,
      ]);
    }
    return $this->render("index.html.twig");
  }

  // Fetch the list of config for the character
  #[Route('/{id<\d+>}/config', name: 'character_configuration')]
  public function listOptions(Request $request, Character $character): Response
  {
    if ($request->isXmlHttpRequest()) {

      $template = "_show";
      
      return $this->render("character_sheet/elements/config.html.twig", [
        'character' => $character,
      ]);
    }
    return $this->render("index.html.twig");
  }


  #[Route('/{id<\d+>}/note/new', name: 'character_note_new', methods: ['GET', 'POST'])]
  public function addNote(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

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
    $form = $this->createForm(CharacterNoteForm::class, $note, $options);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $character->addNote($note);
      $this->dataService->save($note);

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'notes']);
    }
    return $this->render('character_sheet/notes/new.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/notes/{note}/edit', name: 'character_note_edit', methods: ['GET', 'POST'])]
  public function editNote(Request $request, Character $character, CharacterNote $note): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $options = [];
    $form = $this->createForm(CharacterNoteForm::class, $note, $options);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'notes']);
    }
    return $this->render('character_sheet/notes/edit.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/notes/{note}/delete', name: 'character_note_delete', methods: ['GET'])]
  public function deleteNote(Request $request, Character $character, CharacterNote $note): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $this->dataService->remove($note);
    $this->addFlash('notice', 'character.note.deleted');

    return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'notes']);
  }

  #[Route('/{id<\d+>}/wounds/update', name: 'character_wounds_update', methods: ['POST'])]
  public function updateWounds(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      if ($data->action == "take") {
        $this->service->takeWound($character, $data->value);
      } else {
        $this->service->healWound($character, $data->value);
      }

      return new JsonResponse($data);
    } else {

      return $this->redirectToRoute('character_index', []);
    }
  }

  #[Route('/{id<\d+>}/trait/update', name: 'character_trait_update', methods: ['POST'])]
  public function updateTrait(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateTrait($character, $data);
      return new JsonResponse('ok');
    } else {
      return $this->redirectToRoute('character_index', []);
    }
  }

  #[Route('/{id<\d+>}/lesser/trait/update', name: 'character_lesser_trait_update', methods: ['POST'])]
  public function updateLesserTrait(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateTrait($character, $data, true);
      return new JsonResponse('ok');
    } else {
      return $this->redirectToRoute('character_index', []);
    }
  }

  #[Route('/{id<\d+>}/status/update', name: 'character_status_update', methods: ['POST'])]
  public function updateStatus(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $character->setStatus($data->status);
      $this->dataService->update($character);
      return new JsonResponse();
    } else {
      return $this->redirectToRoute('index');
    }
  }

  #[Route('/{id<\d+>}/experience/update', name: 'character_experience_update', methods: ['POST'])]
  public function updateExperience(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateExperience($character, $data);
      return new JsonResponse(['used' => $character->getXpUsed(), 'total' => $character->getXpTotal()]);
    } else {
      return $this->redirectToRoute('character_index', []);
    }
  }

  #[Route('/{id<\d+>}/avatar/update', name: 'character_avatar_update', methods: ['POST'])]
  public function updateAvatar(Request $request, Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $character);

    if ($request->isXmlHttpRequest()) {
      $avatar = $request->files->get('avatar')['upload'];
      $path = $this->getParameter('characters_directory');
      if (!is_null($avatar) && is_string($path)) {
        $filename = $this->dataService->upload($avatar, $path, "character-{$character->getId()}");

        if (!is_null($filename)) {
          $old = $character->getAvatar();
          $character->setAvatar($filename);
          $this->dataService->update($character);
          if (!empty($old) && $old != 'default.jpg') {
            $this->dataService->removeFile("$path/$old");
          }
          return new JsonResponse($filename);
        }
      }

      return new JsonResponse(null, 204);
    } else {

      return $this->redirectToRoute('character_index', []);
    }
  }

  #[Route('/{id<\d+>}/avatar/crop', name: 'character_avatar_crop')]
  public function cropAvatar(Request $request, Character $character, CropperInterface $cropper)
  {
    $filename = addslashes($this->getParameter('characters_directory')."/{$character->getAvatar()}");
    if (strpos($filename, "?")) {
      $filename = substr($filename, 0, strpos($filename, "?"));
    }

    $crop = $cropper->createCrop($filename);

    $form = $this->createFormBuilder(['crop' => $crop])
      ->add('crop', CropperType::class, [
        'public_url' => $this->getParameter('characters_direct_directory')."/{$character->getAvatar()}",
        'cropper_options' => [
          'dragMode' => 'move',
          'aspectRatio' => null,
          'initialAspectRatio' => 1/1
        ],
      ])
      ->getForm()
    ;

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $avatar = $crop->getCroppedImage(pathinfo($filename, PATHINFO_EXTENSION), 100);
      file_put_contents($filename, $avatar);
      $character->updateAvatar();
      $this->dataService->update($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    return $this->render('character_sheet/elements/avatar/crop.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id<\d+>}/morality/increase', name: 'character_morality_increase', methods: ['POST'])]
  public function moralityIncrease(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $this->service->moralityIncrease($character, (bool)$request->request->get('derangement'), (bool)$request->request->get('free'));

    $this->addFlash('notice', ["character.morality.increase", ['name' => $character]]);
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }

  #[Route('/{id<\d+>}/morality/decrease', name: 'character_morality_decrease', methods: ['POST'])]
  public function moralityDecrease(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $this->service->moralityDecrease($character, (int)$request->request->get('derangement'), $request->request->get('details'));

    $this->addFlash('notice', ["character.morality.decrease", ['name' => $character]]);
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }

  #[Route('/{id<\d+>}/derangement/new', name: 'character_derangement_new', methods: ['POST'])]
  public function derangementNew(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);
    $this->service->newCharacterDerangement($character, (int)$request->request->get('derangement'), $request->request->get('details'));

    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }

  #[Route('/{id<\d+>}/ability/remove', name: 'character_ability_removal', methods: ['POST'])]
  public function abilityRemoval(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $element = $this->service->removeAbility($character, $request->request->all());
    if ($element) {
      $this->addFlash("warning", ["character.ability.remove", ['type' => $request->request->get('type'), 'element' => $element, 'method' => $request->request->get('method')]]);
    } else {
      $this->addFlash("error", ["character.ability.not.removed", ['type' => $request->request->get('type'), 'method' => $request->request->get('method')]]);
    }
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }

  #[Route('/{id<\d+>}/status/add', name: 'character_status_effect_add', methods: ['POST'])]
  public function statusEffectAdd(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $data = $request->request->all();
    $statusEffect = new StatusEffect();
    $statusEffect->setName($data['name']);
    $statusEffect->setType($data['type']);
    $statusEffect->setValue(intval($data['value']));
    $statusEffect->setDescription($data['description']);
    if (isset($data['elements'])) {
      $statusEffect->setChoice($data['elements']);
    }
    if (isset($data['icon'])) {
      $statusEffect->setIcon($data['icon']);
    } else {
      $statusEffect->setIcon('info');
    }

    $character->addStatusEffect($statusEffect);
    $this->dataService->save($statusEffect);
    // $element = $this->service->removeAbility($character, $request->request->all());
    // if ($element) {
    //   $this->addFlash("warning", ["character.ability.remove", ['type' => $request->request->get('type'), 'element' => $element, 'method' => $request->request->get('method')]]);
    // } else {
    //   $this->addFlash("error", ["character.ability.not.removed", ['type' => $request->request->get('type'), 'method' => $request->request->get('method')]]);
    // }
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
  }

  private function getSkillList(Character $character)
  {
    if ($character->isAncient()) {
      return $this->ancientSkills;
    }

    return $this->skills;
  }
}
