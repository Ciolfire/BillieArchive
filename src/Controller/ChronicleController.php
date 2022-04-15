<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Human;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Discipline;
use App\Form\CharacterType;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\CharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    $availablePlayers = $this->doctrine->getRepository(User::class)->getAvailablePlayersForChronicle($chronicle->getStoryteller(), $chronicle->getPlayers());
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