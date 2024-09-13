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
use App\Entity\Roll;
use App\Form\CharacterAccessType;
use App\Form\CharacterInfoAccessType;
use App\Form\CharacterNoteType;
use App\Form\CharacterType;
use App\Form\Vampire\VampireType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\ItemService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\LeagueMarkdown;
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

  public function __construct(DataService $dataService, CreationService $creationService, CharacterService $service, ItemService $itemService)
  {
    $this->dataService = $dataService;
    $this->creationService = $creationService;
    $this->service = $service;
    $this->itemService = $itemService;
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

  #[Route("/list/{type}/{id<\d+>}", name: "character_list", methods: ["GET"])]
  public function list(string $type = null, int $id = null) : Response
  {
    $characters = $this->dataService->getList($type, $id, Character::class, "getCharacters");
    $characters = $this->service->sortCharacters(...$characters);;
    return $this->render('character/index.html.twig', [
      'characters' => $characters,
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
        $this->creationService->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->getResolve() + $character->getAttributes()->getComposure());

      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
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
    $this->denyAccessUnlessGranted('ROLE_GM');
    
    $character->setIsNpc(false);
    $character->setIsPremade(true);

    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterType::class, $character);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->creationService->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->creationService->getSpecialties($character, $form);
      // We make sure the willpower is correct
      $character->setWillpower($character->getAttributes()->getResolve() + $character->getAttributes()->getComposure());
      $this->dataService->save($character);

      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
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
  public function show(Request $request, Character $character, FormFactoryInterface $formFactory): Response
  {
    if ($character->getPlayer() != $this->getUser() && ($character->getChronicle() && $character->getChronicle()->getStoryteller() != $this->getUser())) {
      if ($this->getUser()->getRole() != 'ROLE_GM')
        return $this->redirectToRoute('character_peek', ['id' => $character->getId()]);
    }
    $this->dataService->loadMeritsPrerequisites($character->getMerits());
    $type = $this->dataService->findOneBy(ContentType::class, ['name' => $character->getType()]);
    $derangements = $this->dataService->findBy(Derangement::class, ['type' => [$type->getid(), null]], ['name' => 'ASC']);
    $rolls = $this->dataService->findBy(Roll::class, ['isImportant' => "true"], ['name' => 'ASC']);

    $removables = $this->service->getRemovableAttributes($character);

    
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
    return $this->render("character_sheet_type/{$character->getType()}/show.html.twig", [
      'character' => $character,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'rolls' => $rolls,
      'setting' => $character->getSetting(),
      'removables' => $removables,
      'derangements' => $derangements,
      'avatarForm' => $avatarForm->createView(),
    ]);
  }

  #[Route('/{id<\d+>}/edit', name: 'character_edit', methods: ['GET', 'POST'])]
  #[IsGranted('edit', 'character')]
  public function edit(FormFactoryInterface $formFactory, Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

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
      $spent = $this->service->editCharacter($character, $form->getExtraData());

      $this->addFlash('success', ["character.edit.success", ['%count%' => $spent]]);
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }

    $merits = $this->service->loadMerits($character, false);

    return $this->render("character_sheet_type/{$character->getType()}/edit.html.twig", [
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
  public function delete(Character $character): Response
  {
    $this->denyAccessUnlessGranted('delete', $character);

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

  #[Route('/{id<\d+>}/peek', name: 'character_peek', methods: ['GET'])]
  public function peek(Request $request, Character $character): Response
  {
    if ($character instanceof Character) {
      $peeker = $character->getChronicle()->getCharacter($this->getUser());
      if ($peeker instanceof Character) {
        return $this->redirectToRoute('character_peek_as', ['id' => $character->getId(), 'peeker' => $peeker->getId()]);
      }
    }

    $this->addFlash('notice', 'character.peek.declined');
    return $this->redirect($request->headers->get('referer'));
  }

  #[Route('/{id<\d+>}/peek/{peeker}', name: 'character_peek_as', methods: ['GET'])]
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
    return $this->render("character_sheet_type/{$character->getType()}/peek.html.twig", [
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

  #[Route('/{id<\d+>}/lesser/add', name: 'character_lesser_add', methods: ['GET', 'POST'])]
  public function applyLesserTemplate(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $oldTemplate = $character->getLesserTemplate();
    $templates = $this->service->getAllAvailableLesserTemplates($oldTemplate);
    $form = $this->createFormBuilder(null, ['translation_domain' => 'app'])
      ->add('template', ChoiceType::class, [
        'label' => 'label.single',
        'choices' => $templates,
        'translation_domain' => 'content-type',
      ])
      ->add('submit', SubmitType::class, ['label' => 'Apply'])
      ->getForm();
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (!is_null($oldTemplate)) {
        if ($oldTemplate->getType() == $form->getData()['template']) {
          // No change in template, shouldn't happen, return
          $this->addFlash('error', 'character.template.lesser.same', [
            'name' => $character->getName(),
            'type' => $oldTemplate->getType(),
          ]);
          return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
        }
        // We deactivate the old template
        $oldTemplate->setIsActive(false);
        $this->addFlash('notice', 'character.template.lesser.deactivated', [
          'name' => $character->getName(),
          'old' => $oldTemplate->getType(),
        ]);
      }

      return $this->redirectToRoute('character_ghoulify', ['id' => $character->getId()]);
    }
    return $this->render('character/lesser/add.html.twig', [
      'form' => $form,
      'character' => $character,
    ]);
  }

  #[Route('/{id<\d+>}/lesser/remove', name: 'character_lesser_remove', methods: ['GET'])]
  public function removeLesserTemplate(Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $type = $character->getLesserTemplate()->getType();
    $character->getLesserTemplate()->setIsActive(false);
    $this->dataService->update($character);

    $this->addFlash('notice', ["character.template.lesser.remove", ['name' => $character, 'type' => $type]]);
    return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
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

  #[Route('/{id<\d+>}/access/add', name: 'character_access_add', methods: ['GET', 'POST'])]
  public function addAccess(Request $request, Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $access = new CharacterAccess();

    $access->setTarget($character);
    try {
      $form = $this->createForm(CharacterAccessType::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
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

    $form = $this->createForm(CharacterAccessType::class, $access, ['path' => $this->getParameter('characters_direct_directory')]);
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

    $form = $this->createForm(CharacterInfoAccessType::class, $character, ['path' => $this->getParameter('characters_direct_directory'),]);
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

  #[Route('/{id<\d+>}/item/list', name: 'character_item_list')]
  public function listItem(Character $character): Response
  {
    $this->denyAccessUnlessGranted('edit', $character);

    $templates = $this->dataService->findBy(Item::class, ['owner' => null, 'homebrewFor' => [null, $character->getChronicle()->getId()]], ['name' => 'ASC']);

    return $this->render('character_sheet/items/list.html.twig', [
      'character' => $character,
      'templates' => $templates,
    ]);
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
    $form = $this->createForm(CharacterNoteType::class, $note, $options);
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
    $form = $this->createForm(CharacterNoteType::class, $note, $options);
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
        $filename = $this->dataService->upload($avatar, $path);

        if (!is_null($filename)) {
          $old = $character->getAvatar();
          $character->setAvatar($filename);
          $this->dataService->update($character);
          if (!empty($old)) {
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
}
