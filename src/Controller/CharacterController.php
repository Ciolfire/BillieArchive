<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Character;
use App\Entity\CharacterNote;
use App\Entity\Chronicle;
use App\Entity\Human;
use App\Form\CharacterNoteType;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\DataService;
use App\Service\VampireService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\LeagueMarkdown;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/character')]
class CharacterController extends AbstractController
{
  private $dataService;
  private $service;
  private $vService;
  private $create;
  private $attributes;
  private $skills;

  public function __construct(DataService $dataService, CreationService $create, CharacterService $service, VampireService $vService)
  {
    $this->dataService = $dataService;
    $this->service = $service;
    $this->vService = $vService;
    $this->create = $create;
    $this->attributes = $this->service->getSortedAttributes();
    $this->skills = $this->service->getSortedSkills();
  }

  #[Route('/', name: 'character_index', methods: ['GET'])]
  public function index(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => false
      ]),
      'npc' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ]),
    ]);
  }

  #[Route('/npc', name: 'character_npc_index', methods: ['GET'])]
  public function indexNpc(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ]),
    ]);
  }

  #[Route('/new/{isNpc}/{chronicle}/', name: 'character_new', methods: ['GET', 'POST'], defaults: ['isNpc' => 0, 'chronicle' => 0])]
  public function new(Request $request, EntityManagerInterface $entityManager, Chronicle $chronicle = null, bool $isNpc = false): Response
  {
    $character = new Human();
    $character->setChronicle($chronicle);
    $character->setIsNpc($isNpc);
    $character->setPlayer($this->getUser());
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

    return $this->render('character/new.html.twig', [
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

    return $this->render('character_sheet/'.$character->getType().'/show.html.twig', [
      'character' => $character,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'type' => $character->getType(),
    ]);
  }

  #[Route('/{id<\d+>}/edit', name: 'character_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Character $character): Response
  {

    $merits = $this->service->filterMerits($character, false);
    $form = $this->createForm(CharacterType::class, $character, ['is_edit' => true]);
    $form->handleRequest($request);
    $extraData = $form->getExtraData();

    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($extraData['merits'])) {
        $this->create->addMerits($character, $extraData['merits']);
      }
      if (isset($extraData['meritsUp'])) {
        $this->create->updateMerits($character, $extraData['meritsUp']);
      }
      if (isset($extraData['specialties'])) {
        $this->create->addSpecialties($character, $extraData['specialties']);
      }
      if (isset($extraData['willpower'])) {
        $this->service->updateWillpower($character, $extraData['willpower']);
      }
      if ($character->getType() == "vampire") {
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

    return $this->render('character_sheet/'.$character->getType().'/edit.html.twig', [
      'character' => $character,
      'type' => $character->getType(),
      'form' => $form,
      'attributes' => $this->attributes,
      'skills' => $this->skills,
      'merits' => $merits,
      $character->getType() => $this->service->getSpecial($character),
    ]);
  }

  #[Route('/{id<\d+>}/delete', name: 'character_delete', methods: ['POST'])]
  public function delete(Request $request, Character $character, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $character->getId(), $request->request->get('_token'))) {
      $this->dataService->remove($character);
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
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

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'background'], Response::HTTP_SEE_OTHER);
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
    $note->setAuthor($this->getUser());
    $note->setCharacter($character);

    $latestNote = $character->getNotes()->first();
    $options = [];
    if ($latestNote instanceof CharacterNote) {
      $options['date'] = $latestNote->getAssignedAt()->format('Y-m-d H:i:s');
    }
    $form = $this->createForm(CharacterNoteType::class, $note, $options);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $character->addNote($note);
      $this->dataService->save($note);

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'notes'], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character/notes/new.html.twig', [
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
      $this->dataService->flush($note);

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => "notes"], Response::HTTP_SEE_OTHER);
    }
    return $this->render('character/notes/edit.html.twig', [
      'note' => $note,
      'form' => $form,
    ]);
  }

  #[Route('/{id<\d+>}/wounds/update', name: 'character_wounds_update', methods: ['POST'])]
  public function updateWounds(Request $request, Character $character): JsonResponse
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
  public function updateTrait(Request $request, Character $character): JsonResponse
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
  public function updateExperience(Request $request, Character $character): JsonResponse
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
  public function updateAvatar(Request $request, Character $character, LoggerInterface $logger): JsonResponse
  {
    if ($request->isXmlHttpRequest()) {
      $avatar = $request->files->get('avatar')['upload'];
      if (!is_null($avatar)) {
        $file = $this->dataService->upload($avatar, $this->getParameter('characters_directory'), $character->getId());
      }

      return new JsonResponse($file);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}
