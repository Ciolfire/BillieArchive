<?php

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Description;
use App\Form\ClanType;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class ClanController extends AbstractController
{
  private $dataService;
  private $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/clans', name: 'clan_index', methods: ['GET'])]
  public function clans(): Response
  {
    return $this->render('vampire/clan/index.html.twig', [
      'clans' => $this->dataService->findBy(Clan::class, ['isBloodline' => false]),
      'bloodlines' => $this->service->getBloodlines(),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'search' => [
        'parent' => ['Daeva', 'Gangrel', 'Mekhet', 'Nosferatu', 'Ventrue'],
      ],
    ]);
  }

  #[Route('/clan/{id<\d+>}', name: 'clan_show', methods: ['GET'])]
  public function clanShow(Clan $clan): Response
  {
    return $this->render('vampire/clan/show.html.twig', [
      'clan' => $clan,
      'entity' => 'clan',
    ]);
  }

  #[Route('/clan/{bloodline<\d+>?0}/new', name: 'clan_new', methods: ['GET', 'POST'])]
  public function clanNew(bool $bloodline, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $clan = new Clan($bloodline);
    $form = $this->createForm(ClanType::class, $clan);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      if (!is_null($emblem)) {
        $clan->setEmblem($this->dataService->upload($emblem, $this->getParameter('clans_emblems_directory')));
      }
      $this->dataService->save($clan);

      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('vampire/clan/form.html.twig', [
      'action' => 'new',
      'entity' => 'clan',
      'trans' => 'clan.bloodline.',
      'form' => $form,
    ]);
  }

  #[Route('/clan/{id<\d+>}/edit', name: 'clan_edit', methods: ['GET', 'POST'])]
  public function clanEdit(Request $request, Clan $clan): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(ClanType::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      if (!is_null($emblem)) {
        $clan->setEmblem($this->dataService->upload($emblem, $this->getParameter('clans_emblems_directory')));
      }
      $this->dataService->flush();

      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    if ($clan->isBloodline()) {
      $entity = 'bloodline';
      $trans = 'clan.bloodline.';
    } else {
      $entity = 'clan';
      $trans = 'clan.';

    }

    return $this->render('vampire/clan/form.html.twig', [
      'action' => 'edit',
      'trans' => $trans,
      'entity' => $entity,
      'form' => $form,
    ]);
  }

  #[Route('/bloodlines', name: 'bloodline_index', methods: ['GET'])]
  public function bloodlines(): Response
  {
    return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('{id<\d+>}/bloodline/join', name: 'vampire_bloodline_join', methods: ['GET', 'POST'])]
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
      $vampire->setClan($this->dataService->find(Clan::class, $request->request->get('bloodline')));
      $this->dataService->flush();

      return $this->redirectToRoute('character_show', ['id' => $vampire->getId()], Response::HTTP_SEE_OTHER);
    }

    $bloodlines = $this->dataService->findBy(Clan::class, ['isBloodline' => true, 'parentClan' => $vampire->getClan()]);

    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
    ]);
  }

  #[Route("/clan/{filter<\w+>}/{id<\d+>}", name: "clan_list", methods: ["GET"])]
  public function clanList($filter, $id)
  {
    switch ($filter) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        break;
      
      case 'chronicle':
          /** @var Chronicle */
          $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
          break;
        default:
        # code...
        break;
    }

    return $this->render('vampire/clan/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'clans' => $item->getClans(),
      'bloodlines' => $item->getBloodlines(),
      'search' => [],
    ]);
  }
}
