<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Derangement;
use App\Entity\Description;
use App\Form\DerangementType;
use App\Repository\DerangementRepository;
use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/derangement")]
class DerangementController extends AbstractController
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/new", name:"derangement_new", methods:["GET", "POST"])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $derangement = new Derangement();

    $form = $this->createForm(DerangementType::class, $derangement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($derangement);
      $entityManager->flush();

      return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name:"derangement_show", methods:["GET"])]
  public function show(Derangement $derangement): Response
  {
    return $this->render('element/show.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"derangement_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Derangement $derangement, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(DerangementType::class, $derangement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/translate/{language}", name:"derangement_translate", methods:["GET", "POST"])]
  public function translate(Request $request, Derangement $derangement, $language, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(DerangementType::class, $derangement);
    $form->handleRequest($request);
    $derangement->setTranslatableLocale($language); // change locale

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($derangement);
      $entityManager->flush();

      return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"derangement_delete", methods:["POST"])]
  public function delete(Request $request, Derangement $derangement, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $derangement->getId(), $request->request->get('_token'))) {
      $entityManager->remove($derangement);
      $entityManager->flush();
    }

    return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("/{type}/{id<\d+>}", name: "derangement_list", methods: ["GET"])]
  public function list($type = null, $id = null)
  {
    /** @var DerangementRepository $repo */
    $repo = $this->dataService->getRepository(Derangement::class);

    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $derangements = $item->getDerangements();
        $setting = $item->getSetting();
        break;
      default:
        $derangements = $repo->findMild();
        $setting = "human";
        break;
    }
    // dd($derangements);
    return $this->render('derangement/list.html.twig', [
      'type' => $setting,
      'derangements' => $derangements,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'derangement']),
      // 'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }
}
