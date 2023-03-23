<?php

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Description;
use App\Entity\Devotion;
use App\Form\DevotionType;
use App\Service\DataService;
use App\Service\VampireService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class DevotionController extends AbstractController
{
  private $dataService;
  private $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }
  
  #[Route('/devotions', name: 'devotion_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);

    foreach ($devotions as $devotion) {
      /** @var Devotion $devotion */
      foreach ($devotion->getprerequisites() as $prerequisite) {
        $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
      }
    }

    return $this->render('vampire/devotion/index.html.twig', [
      'devotions' => $devotions,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  #[Route("/devotion/{type<\w+>}/{id<\d+>}", name: "devotion_list", methods: ["GET"])]
  public function devotionList($type, $id)
  {
    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        break;
      
      default:
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        break;
    }

    $devotions = $item->getDevotions();
    foreach ($devotions as $devotion) {
      /** @var Devotion $devotion */
      foreach ($devotion->getprerequisites() as $prerequisite) {
        $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
      }
    }

    return $this->render('vampire/devotion/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character',
      'type' => 'vampire',
      'devotions' => $devotions,
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
      'form' => $form,
      'type' => 'vampire',
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
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  #[Route('/devotion/{id<\d+>}/show', name: 'vampire_devotion_show', methods: ['GET', 'POST'])]
  public function devotionShow(Devotion $devotion, Request $request): Response
  {
    foreach ($devotion->getprerequisites() as $prerequisite) {
      $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
    }

    return $this->render('vampire/devotion/show.html.twig', [
      'devotion' => $devotion,
      'type' => 'vampire',
    ]);
  }
}
