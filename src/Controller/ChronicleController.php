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
      return $this->redirectToRoute('party_index', ['id' => $chronicle->getId()]);
    }

    return $this->renderForm('chronicle/new.html.twig', [
      'chronicle' => $chronicle,
      'form' => $form,
    ]);
  }


  /**
   * @Route("/party/{id}", name="party_index", methods={"GET"})
   */
  public function party(Chronicle $chronicle)
  {
    return $this->render('chronicle/party/index.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
    ]);
  }

  /**
   * @Route("/party/add/{id}", name="party_add_player")
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
      ->add('Add', SubmitType::class)
      ->getForm();
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        /** @var User */
        $player = $form->getData()['player'];
        $chronicle->addPlayer($player);
        $this->doctrine->getManager()->flush();
        $this->addFlash('notice', "{$player->getUserIdentifier()} added to the campaign");
        return $this->redirectToRoute('party_index', ['id' => $chronicle->getId()]);
      }
    } else {
      $this->addFlash('notice', 'No player available');
      return $this->redirectToRoute('party_index', ['id' => $chronicle->getId()]);
    }

    return $this->renderForm('chronicle/party/addPlayer.html.twig', [
      'chronicle' => $chronicle,
      'type' => $chronicle->getType(),
      'form' => $form,
    ]);
  }
}