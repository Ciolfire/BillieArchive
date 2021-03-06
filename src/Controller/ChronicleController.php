<?php

namespace App\Controller;

use App\Entity\Chronicle;
use App\Entity\User;
use App\Form\ChronicleType;
use App\Repository\UserRepository;
use App\Service\CharacterService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/chronicle")
 */
class ChronicleController extends AbstractController
{
  private $doctrine;
  private $service;

  public function __construct(ManagerRegistry $doctrine, CharacterService $service)
  {
    $this->doctrine = $doctrine;
    $this->service = $service;
  }

  /**
   * @Route("/new", name="chronicle_new")
   */
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

  /**
   * @Route("/{id}/", name="chronicle_show", methods={"GET"})
   */
  public function show(Chronicle $chronicle)
  {
    return $this->render('chronicle/show.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  /**
   * @Route("/{id}/party", name="chronicle_party_index", methods={"GET"})
   */
  public function party(Chronicle $chronicle)
  {
    return $this->render('chronicle/party/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  /**
   * @Route("/{id}/npc", name="chronicle_npc_index", methods={"GET"})
   */
  public function npc(Chronicle $chronicle)
  {
    return $this->render('character/npc/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  /**
   * @Route("/{id}/npc/add", name="chronicle_npc_add", methods={"GET"})
   */
  public function addNpc(Chronicle $chronicle)
  {
    return $this->redirectToRoute('character_new', ['chronicle' => $chronicle->getId(), 'isNpc' => true]);

    // return $this->render('character/npc/index.html.twig', [
    //   'chronicle' => $chronicle,
    //   'type' => $chronicle->getType(),
    // ]);
  }

  /**
   * @Route("/{id}/party/add", name="chronicle_add_player")
   */
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

  /**
   * @Route("/{id}/party/remove", name="chronicle_remove_player")
   */
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
}