<?php

declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Description;
use App\Form\Vampire\ClanForm;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class ClanController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route("/{id<\d+>}/fetch", name:"vampire_clan_fetch", methods:["GET"])]
  public function fetch(Clan $clan): Response
  {
    return $this->render("vampire/clan/_card.html.twig", [
      'element' => 'roll',
      'clan' => $clan,
      'isShown' => true,
    ]);
  }

  #[Route('/wiki/clans', name: 'vampire_clan_index', methods: ['GET'])]
  public function clans(): Response
  {
    return $this->render('vampire/clan/list.html.twig', [
      'clans' => $this->dataService->findBy(Clan::class, [
        'isBloodline' => false,
        'isAncient' => false,
        'homebrewFor' => null,
      ]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'search' => [
        'parent' => ['Daeva', 'Gangrel', 'Mekhet', 'Nosferatu', 'Ventrue'],
      ],
    ]);
  }

  #[Route('/wiki/bloodlines', name: 'vampire_bloodline_index', methods: ['GET'])]
  public function bloodlines(): Response
  {
    return $this->bloodlinesFilter($this->service->getBloodlines());
  }

  #[Route("/wiki/clans/list/{filter<\w+>}/{id<\w+>}", name: "vampire_clan_list", methods: ["GET"])]
  public function clanList(string $filter, mixed $id): Response
  {
    switch ($filter) {
      case 'chronicle':
        /** @var Chronicle */
        $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
        $back = ['path' => 'homebrew_index', 'params' => [
          'id' => $id,
        ]];
        break;
      case 'book':
      default:
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $back = ['path' => 'book_index', 'params' => [
          'setting' => 'vampire',
          '_fragment' => $id
        ]];
    }
    return $this->bloodlinesFilter($item->getBloodlines(), $item->getClans(), $filter, $id);
  }

  #[Route("/wiki/clans/ancient/list", name: "vampire_clan_ancient_list", methods: ["GET"])]
  public function ancientClanList()
  {
    $clans = $this->dataService->findBy(Clan::class, ['isAncient' => true, 'isBloodline' => false]);
    return $this->render('vampire/clan/list.html.twig', [
      'bloodlines' => $this->dataService->findBy(Clan::class, ['isAncient' => true, 'isBloodline' => true]),
      'clans' => $clans,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'setting' => "vampire",
      'ancient' => true,
      'search' => [
        'parents' => $clans,
      ],
    ]);
  }

  #[Route('/wiki/clan/{id<\d+>}', name: 'vampire_clan_show', methods: ['GET'])]
  public function clanShow(Request $request, Clan $clan): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }
    
    return $this->render("vampire/clan/$template.html.twig", [
      'clan' => $clan,
      'entity' => 'clan',
    ]);
  }

  #[Route('/clan/{bloodline<\d+>?0}/{ancient<\d+>?0}/new', name: 'vampire_clan_new', methods: ['GET', 'POST'])]
  public function clanNew(bool $bloodline, bool $ancient, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $clan = new Clan($bloodline, $this->dataService->getItem($request->request->get('filter'), $request->request->get('id')), $ancient);
    $form = $this->createForm(ClanForm::class, $clan);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $filePath = $this->getParameter('clans_emblems_directory');
      
      $emblem = $form->get('emblem')->getData();
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $clan->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      
      $symbol = $form->get('symbol')->getData();
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $clan->setSymbol($this->dataService->upload($symbol, $filePath));
      }
      
      $this->dataService->save($clan);

      $this->addFlash('success', ["general.new.done", ['%name%' => $clan->getName()]]);
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('vampire_bloodline_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
      }
      return $this->redirectToRoute('vampire_clan_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('vampire/clan/form.html.twig', [
      'action' => 'new',
      'entity' => 'bloodline',
      'trans' => 'clan.bloodline.',
      'form' => $form,
    ]);
  }

  #[Route('/clan/{id<\d+>}/edit', name: 'vampire_clan_edit', methods: ['GET', 'POST'])]
  public function clanEdit(Request $request, Clan $clan): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(ClanForm::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $filePath = $this->getParameter('clans_emblems_directory');
      
      $emblem = $form->get('emblem')->getData();
      if ($emblem instanceof UploadedFile && is_string($filePath)) {
        $clan->setEmblem($this->dataService->upload($emblem, $filePath));
      }
      
      $symbol = $form->get('symbol')->getData();
      if ($symbol instanceof UploadedFile && is_string($filePath)) {
        $clan->setSymbol($this->dataService->upload($symbol, $filePath));
      }
      
      $this->dataService->update($clan);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $clan->getName()]]);
      if ($clan->isAncient()) {
        return $this->redirectToRoute('vampire_clan_ancient_list', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
      }
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('vampire_bloodline_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
      }
      return $this->redirectToRoute('vampire_clan_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
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

  #[Route('/clan/{id<\d+>}/delete', name: 'vampire_clan_delete', methods: ['GET'])]
  public function delete(Clan $clan): Response
  {
    $this->denyAccessUnlessGranted('delete', $clan);

    try {
      $this->dataService->remove($clan);
      $this->addFlash('success', ["clan.delete.success", ['%name%' => $clan->getName()]]);
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('vampire_bloodline_index');
      } else {
        return $this->redirectToRoute('vampire_clan_index');
      }
    } catch (\Throwable $th) {
      $this->addFlash('error', ["clan.delete.failed", ['%name%' => $clan->getName()]]);
    }


    return $this->redirectToRoute('vampire_clan_show', ['id' => $clan->getId()]);
  }

  #[Route('/{id<\d+>}/bloodline/join', name: 'vampire_bloodline_join', methods: ['GET', 'POST'])]
  public function bloodlineJoin(Request $request, Vampire $vampire): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    if ($vampire->getPlayer() != $this->getUser() && ($vampire->getChronicle() && $vampire->getChronicle()->getStoryteller() != $this->getUser())) {
      $this->addFlash('notice', 'denied');
      return $this->redirectToRoute('character_index');
    }

    // Form submited
    if ($request->request->get('bloodline')) {
      $bloodline = $this->dataService->find(Clan::class, $request->request->get('bloodline'));
      if ($bloodline instanceof Clan) {
        $vampire->setClan($bloodline);
        $this->dataService->flush();
      }

      $this->addFlash('success', ["clan.bloodline.join", ['%name%' => $vampire->getName(), '%bloodline%' => $vampire->getClan()->getName()]]);
      return $this->redirectToRoute('character_show', ['id' => $vampire->getId()], Response::HTTP_SEE_OTHER);
    }

    $bloodlines = $this->dataService->findBy(Clan::class, ['isBloodline' => true, 'parentClan' => $vampire->getClan()], ['name' => 'ASC']);

    return $this->render('vampire/bloodline/join.html.twig', [
      'vampire' => $vampire,
      'bloodlines' => $bloodlines,
    ]);
  }

  // Methods
  /** Prepare the clans data to be displayed */
  private function bloodlinesFilter($bloodlines, $clans=null, $filter=null, $id=null)  {
    $parents = [];
    foreach ($bloodlines as $bloodline) {
      /** @var Clan $bloodline */
      if ($clan = $bloodline->getParentClan()) {
        $parents[$clan->getId()] = $clan;
      }
    }

    return $this->render('vampire/clan/list.html.twig', [
      'bloodlines' => $bloodlines,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'bloodline']),
      'setting' => "vampire",
      'clans' => $clans,
      'filter' => $filter,
      'id' => $id,
      'search' => [
        'parents' => $parents,
      ],
    ]);
  }
}
