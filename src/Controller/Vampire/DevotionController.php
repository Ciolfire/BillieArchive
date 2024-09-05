<?php declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Description;
use App\Entity\Devotion;
use App\Form\Vampire\DevotionType;
use App\Service\DataService;
use App\Service\VampireService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class DevotionController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }
  
  #[Route('/devotions', name: 'vampire_devotion_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);

    /** @var Devotion $devotion */
    foreach ($devotions as $devotion) {
      $this->dataService->loadPrerequisites($devotion);
    }

    return $this->render('vampire/devotion/index.html.twig', [
      'devotions' => $devotions,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character'
    ]);
  }

  #[Route("/devotion/{filter<\w+>}/{id<\d+>}", name: "devotion_list", methods: ["GET"])]
  public function devotionList(string $filter, int $id): Response
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

    $devotions = $item->getDevotions();
    /** @var Devotion $devotion */
    foreach ($devotions as $devotion) {
      $this->dataService->loadPrerequisites($devotion);
    }

    return $this->render('vampire/devotion/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character',
      'devotions' => $devotions,
      'back' => $back,
      'search' => [],
    ]);
  }

  #[Route('/devotion/new', name: 'vampire_devotion_new', methods: ['GET', 'POST'])]
  public function devotionNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $devotion = new Devotion();
    $form = $this->createForm(DevotionType::class, $devotion);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($devotion);

      return $this->redirectToRoute('vampire_devotion_show', ['id' => $devotion->getId()]);
    }

    return $this->render('vampire/devotion/new.html.twig', [
      'action' => 'new',
      'form' => $form
    ]);
  }

  #[Route('/devotion/{id<\d+>}/edit', name: 'vampire_devotion_edit', methods: ['GET', 'POST'])]
  public function devotionEdit(Devotion $devotion, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DevotionType::class, $devotion);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($devotion);

      return $this->redirectToRoute('vampire_devotion_show', ['id' => $devotion->getId()]);
    }

    return $this->render('vampire/devotion/new.html.twig', [
      'action' => 'edit',
      'form' => $form
    ]);
  }

  #[Route('/devotion/{id<\d+>}/show', name: 'vampire_devotion_show', methods: ['GET', 'POST'])]
  public function devotionShow(Devotion $devotion, Request $request): Response
  {
    $this->dataService->loadPrerequisites($devotion);

    return $this->render('vampire/devotion/show.html.twig', [
      'devotion' => $devotion,
    ]);
  }
}
