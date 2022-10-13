<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\Human;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Discipline;
use App\Form\CharacterType;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use App\Service\VampireService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Extra\Markdown\LeagueMarkdown;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/character")
 */
class CharacterController extends AbstractController
{
  private $doctrine;
  private $service;
  private $vService;
  private $create;

  public function __construct(ManagerRegistry $doctrine, CreationService $create, CharacterService $service, VampireService $vService)
  {
    $this->doctrine = $doctrine;
    $this->service = $service;
    $this->vService = $vService;
    $this->create = $create;
  }

  /**
   * @Route("/", name="character_index", methods={"GET"})
   */
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

  /**
   * @Route("/npc", name="character_npc_index", methods={"GET"})
   */
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

  /**
   * @Route("/new/{isNpc}/{chronicle}/", name="character_new", methods={"GET", "POST"}, defaults={"isNpc": 0, "chronicle": 0})
   */
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
      
      $entityManager->persist($character);
      $entityManager->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('character/new.html.twig', [
      'character' => $character,
      'form' => $form,
      'merits' => $merits,
    ]);
  }

  /**
   * @Route("/{id}", name="character_show", methods={"GET"})
   */
  public function show(Character $character): Response
  {
    if ($character->getPlayer() != $this->getUser() && ($character->getChronicle() && $character->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }

    return $this->render('character/show.html.twig', [
      'character' => $character,
      'type' => $character->getType(),
    ]);
  }

  /**
   * @Route("/{id}/edit", name="character_edit", methods={"GET", "POST"})
   */
  public function edit(Request $request, Character $character, EntityManagerInterface $entityManager): Response
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
      if (isset($extraData['xp'])) {
        $character->spendXp($extraData['xp']['spend']);
      }
      $entityManager->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('character/edit.html.twig', [
      'character' => $character,
      'type' => $character->getType(),
      'form' => $form,
      'merits' => $merits,
      $character->getType() => $this->service->getSpecial($character),
    ]);
  }

  /**
   * @Route("/{id}", name="character_delete", methods={"POST"})
   */
  public function delete(Request $request, Character $character, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $character->getId(), $request->request->get('_token'))) {
      $entityManager->remove($character);
      $entityManager->flush();
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }

  /**
   * @Route("/{id}/embrace", name="character_embrace", methods={"GET", "POST"})
   */
  public function embrace(Request $request, Character $character, EntityManagerInterface $entityManager): Response
  {
    $clans = $this->doctrine->getRepository(Clan::class)->findBy(['parentClan' => null]);
    $attributes = $this->doctrine->getRepository(Attribute::class)->findAll();
    $disciplines = $this->doctrine->getRepository(Discipline::class)->findAll();
    $form = $this->createForm(EmbraceType::class, null, ['clans' => $clans, 'attributes' => $attributes]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->vService->embrace($character, $form);
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }
    return $this->renderForm('vampire/embrace/sheet.html.twig', [
      'character' => $character,
      'clans' => $clans,
      'disciplines' => $disciplines,
      'form' => $form,
      'type' => "vampire",
    ]);
  }

  /**
   * @Route("/{id}/background", name="character_background", methods={"GET", "POST"})
   */
  public function background(Request $request, Character $character): Response
  {
    $converter = new LeagueMarkdown();
    $background = $character->getBackground();
    $form = $this->createFormBuilder()
      ->add('background', CKEditorType::class , ['data' => $converter->convert($background), 'label' => 'background.label', 'translation_domain' => 'character'])
      ->add('save', SubmitType::class, ['label' => 'save', 'translation_domain' => 'app'])
      ->getForm();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $background = $form->get('background')->getData();
      if ($background == null) {
        $background = "";
      }
      $character->setBackground($background);
      $this->doctrine->getManager()->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId(), '_fragment' => 'background'], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('character/edit/background.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}/notes", name="character_notes", methods={"GET", "POST"})
   */
  public function notes(Request $request, Character $character): Response
  {
    $converter = new LeagueMarkdown();
    $notes = $character->getNotes();
    $form = $this->createFormBuilder()
      ->add('notes', CKEditorType::class , ['data' => $converter->convert($notes), 'label' => 'notes.label', 'translation_domain' => 'character'])
      ->add('save', SubmitType::class, ['label' => 'save', 'translation_domain' => 'app'])
      ->getForm();
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $notes = $form->get('notes')->getData();
      if ($notes == null) {
        $notes = "";
      }
      $character->setNotes($notes);
      $this->doctrine->getManager()->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId(),  '_fragment' => 'notes'], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('character/edit/notes.html.twig', [
      'character' => $character,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{character}/wounds/update", name="character_wounds_update", methods={"POST"})
   * @param Character $character
   */
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

  /**
   * @Route("/{character}/trait/update", name="character_trait_update", methods={"POST"})
   * @param Character $character
   */
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

  /**
   * @Route("/{character}/experience/update", name="character_experience_update", methods={"POST"})
   * @param Character $character
   */
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

  /**
   * @Route("/{character}/avatar/update", name="character_avatar_update", methods={"POST"})
   * @param Character $character
   */
  public function updateAvatar(Request $request, Character $character, LoggerInterface $logger): JsonResponse
  {
    if ($request->isXmlHttpRequest()) {
      /** @var UploadedFile $avatarFile */
      $avatarFile = $request->files->get('avatar')['upload'];
      if ($avatarFile) {
        $newFilename = $character->getId().".jpg";
        
        $avatarFile->move(
          $this->getParameter('characters_directory'),
          $newFilename
        );
      }
      return new JsonResponse($newFilename);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}
