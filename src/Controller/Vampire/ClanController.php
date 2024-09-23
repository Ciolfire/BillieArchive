<?php declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Description;
use App\Form\Vampire\ClanType;
use App\Service\DataService;
use App\Service\VampireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

  #[Route('/clans', name: 'clan_index', methods: ['GET'])]
  public function clans(): Response
  {
    return $this->render('vampire/clan/index.html.twig', [
      'clans' => $this->dataService->findBy(Clan::class, ['isBloodline' => false]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'search' => [
        'parent' => ['Daeva', 'Gangrel', 'Mekhet', 'Nosferatu', 'Ventrue'],
      ],
    ]);
  }

  #[Route('/bloodlines', name: 'bloodline_index', methods: ['GET'])]
  public function bloodlines(): Response
  {
    return $this->render('vampire/clan/index.html.twig', [
      'bloodlines' => $this->service->getBloodlines(),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'bloodline']),
      'entity' => 'bloodline',
      'category' => 'character',
      'search' => [
        'parent' => ['Daeva', 'Gangrel', 'Mekhet', 'Nosferatu', 'Ventrue'],
      ],
    ]);
  }

  #[Route('/clans', name: 'clan_and_bloodline_index', methods: ['GET'])]
  public function clansAndBloodline(): Response
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

  #[Route("/clan/{filter<\w+>}/{id<\d+>}", name: "clan_list", methods: ["GET"])]
  public function clanList(string $filter, int $id): Response
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

    return $this->render('vampire/clan/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'clan']),
      'entity' => 'clan',
      'category' => 'character',
      'clans' => $item->getClans(),
      'bloodlines' => $item->getBloodlines(),
      'search' => [],
      'back' => $back,
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
      $path = $this->getParameter('clans_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($path)) {
        $clan->setEmblem($this->dataService->upload($emblem, $path));
      }
      $this->dataService->save($clan);

      $this->addFlash('success', ["general.new.done", ['%name%' => $clan->getName()]]);
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('bloodline_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
      }
      return $this->redirectToRoute('clan_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('vampire/clan/form.html.twig', [
      'action' => 'new',
      'entity' => 'bloodline',
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
      $path = $this->getParameter('clans_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($path)) {
        $clan->setEmblem($this->dataService->upload($emblem, $path));
      }
      $this->dataService->update($clan);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $clan->getName()]]);
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('bloodline_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
      }
      return $this->redirectToRoute('clan_index', ['_fragment' => $clan->getName()], Response::HTTP_SEE_OTHER);
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

  #[Route('/clan/{id<\d+>}/delete', name: 'clan_delete', methods: ['GET'])]
  public function delete(Clan $clan): Response
  {
    $this->denyAccessUnlessGranted('delete', $clan);

    try {
      $this->dataService->remove($clan);
      $this->addFlash('success', ["clan.delete.success", ['%name%' => $clan->getName()]]);
      if ($clan->isBloodline()) {
        return $this->redirectToRoute('bloodline_index');
      } else {
        return $this->redirectToRoute('clan_index');
      }
    } catch (\Throwable $th) {
      $this->addFlash('error', ["clan.delete.failed", ['%name%' => $clan->getName()]]);
    }


    return $this->redirectToRoute('clan_show', ['id' => $clan->getId()]);
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
}
