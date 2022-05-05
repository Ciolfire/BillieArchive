<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\VampireService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/vampire")
 */
class VampireController extends AbstractController
{
  private $doctrine;
  private $service;

  public function __construct(ManagerRegistry $doctrine, VampireService $service)
  {
    $this->doctrine = $doctrine;
    $this->service = $service;
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
   * @Route("/bloodline/join/{id}", name="vampire_bloodline_join", methods={"GET"})
   */
  public function bloodlineJoin(Vampire $vampire): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if ($vampire->getPlayer() != $this->getUser() && ($vampire->getChronicle() && $vampire->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }

    $bloodlines = $this->doctrine->getRepository(Clan::class)->findBy(['parentClan' => $vampire->getClan()]);
    // dd($bloodlines);
    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
      'type' => $vampire->getType(),
    ]);
  }
}
