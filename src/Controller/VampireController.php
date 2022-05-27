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
use Symfony\Component\HttpFoundation\Request;
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
   * @Route("/bloodline/new/", name="vampire_bloodline_new", methods={"GET"})
   */
  public function bloodlineNew(): Response
  {
    return $this->render('vampire/bloodline/new.html.twig', [
      'type' => 'vampire',
    ]);
  }

  /**
   * @Route("{id}/bloodline/join", name="vampire_bloodline_join", methods={"GET", "POST"})
   */
  public function bloodlineJoin(Request $request, Vampire $vampire): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if ($vampire->getPlayer() != $this->getUser() && ($vampire->getChronicle() && $vampire->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'You are not allowed to see this character');
      return $this->redirectToRoute('character_index');
    }

    // Form submited
    if ($request->request->get('bloodline')) {
      $vampire->setClan($this->doctrine->getRepository(Clan::class)->find($request->request->get('bloodline')));
      $this->doctrine->getManager()->flush();

      return $this->redirectToRoute('character_show', ['id' => $vampire->getId()], Response::HTTP_SEE_OTHER);
    }

    $bloodlines = $this->doctrine->getRepository(Clan::class)->findBy(['parentClan' => $vampire->getClan()]);
    // dd($bloodlines);
    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
      'type' => 'vampire',
    ]);
  }
}
