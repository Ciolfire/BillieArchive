<?php

namespace App\Controller\Vampire;

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
      'clans' => $this->dataService->findBy(Clan::class, ['parentClan' => null]),
      'bloodlines' => $this->dataService->findBy(Clan::class, ['parentClan' => !null]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  #[Route('/clan/new', name: 'clan_new', methods: ['GET', 'POST'])]
  public function clanNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $clan = new Clan();

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

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/clan/{id}/edit', name: 'clan_edit', methods: ['GET', 'POST'])]
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

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/bloodline/new', name: 'vampire_bloodline_new', methods: ['GET'])]
  public function bloodlineNew(): Response
  {
    return $this->render('vampire/bloodline/new.html.twig', [
      'type' => 'vampire',
    ]);
  }

  #[Route('{id}/bloodline/join', name: 'vampire_bloodline_join', methods: ['GET', 'POST'])]
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

    $bloodlines = $this->dataService->findBy(Clan::class, ['parentClan' => $vampire->getClan()]);

    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
      'type' => 'vampire',
    ]);
  }
}
