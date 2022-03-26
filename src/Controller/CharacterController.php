<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Clan;
use App\Entity\Discipline;
use App\Form\CharacterType;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use App\Service\CreationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/character")
 */
class CharacterController extends AbstractController
{
  private $doctrine;
  private $service;
  private $create;

  public function __construct(ManagerRegistry $doctrine, CreationService $create, CharacterService $service) {
    $this->doctrine = $doctrine;
    $this->service = $service;
    $this->create = $create;
  }

  /**
   * @Route("/", name="character_index", methods={"GET"})
   */
  public function index(CharacterRepository $characterRepository): Response
  {
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findAll(),
    ]);
  }

  /**
   * @Route("/new", name="character_new", methods={"GET", "POST"})
   */
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $character = new Character();
    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterType::class, $character);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->create->addMerits($character, $form->getExtraData()['merits']);
      }
      $this->create->getSpecialties($character, $form);
      $this->create->getWillpower($character);
      
      $entityManager->persist($character);
      $entityManager->flush();

      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
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
    return $this->render('character/show.html.twig', [
      'character' => $character,
    ]);
  }

  /**
   * @Route("/{id}/edit", name="character_edit", methods={"GET", "POST"})
   */
  public function edit(Request $request, Character $character, EntityManagerInterface $entityManager): Response
  {

    $merits = $this->service->filterMerits($character);
    $form = $this->createForm(CharacterType::class, $character, ['is_edit' => true]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if (isset($form->getExtraData()['merits'])) {
        $this->create->addMerits($character, $form->getExtraData()['merits']);
      }
      if (isset($form->getExtraData()['meritsUp'])) {
        $this->create->updateMerits($character, $form->getExtraData()['meritsUp']);
      }
      if (isset($form->getExtraData()['specialties'])) {
        $this->create->addSpecialties($character, $form->getExtraData()['specialties']);
      }
      if (isset($form->getExtraData()['xp'])) {
        $character->spendXp($form->getExtraData()['xp']['spend']);
      }
      $entityManager->flush();

      return $this->redirectToRoute('character_show', ['id' => $character->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('character/edit.html.twig', [
      'character' => $character,
      'form' => $form,
      'merits' => $merits,
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
    $clans = $this->doctrine->getRepository(Clan::class)->findAll();
    $attributes = $this->doctrine->getRepository(Attribute::class)->findAll();
    $disciplines = $this->doctrine->getRepository(Discipline::class)->findAll();
    $form = $this->createForm(EmbraceType::class, null, ['clans' => $clans, 'attributes' => $attributes]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      dd($form);
    }
    return $this->renderForm('character/embrace.html.twig', [
      'character' => $character,
      'clans' => $clans,
      'disciplines' => $disciplines,
      'form' => $form,
      'type' => "vampire",
    ]);
  }


  /**
   * @Route("/{character}/wounds/update", name="character_wounds_update", methods={"POST"})
   * @param Character $character
   */
  public function updateWounds(Request $request, Character $character)
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
  public function updateTrait(Request $request, Character $character)
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
  public function updateExperience(Request $request, Character $character)
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $this->service->updateExperience($character, $data);
      return new JsonResponse(['used' => $character->getXpUsed(), 'total' => $character->getXpTotal()]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}
