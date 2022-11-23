<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Merit;
use App\Form\MeritType;
use App\Repository\MeritRepository;

use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

#[Route("{_locale<%supported_locales%>?%default_locale%}/merit")]
class MeritController extends AbstractController
{
  private $repository;
  private $dataService;
  private $categories = ['mental', 'physical', 'social'];

  public function __construct(DataService $dataService, MeritRepository $meritRepository)
  {
    $this->repository = $meritRepository;
    $this->dataService = $dataService;
  }

  #[Route("/", name: 'merit_index', methods: ["GET"])]
  public function index(): Response
  {
    return $this->render('merit/index.html.twig', [
      'merits' => $this->dataService->findBy(Merit::class, [], ['name' => 'ASC']),
      'search' => [
        'type' => $this->dataService->getMeritTypes(), // Kinda want to replace for dynamic list
        'category' => $this->categories,
      ],
    ]);
  }

  #[Route("/{type}/{id}", name: "merit_list", methods: ["GET"], requirements: ["id" => "\d+"])]
  public function list($type, $id)
  {
    $search = ['category' => $this->categories];
    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $types = $this->dataService->getMeritTypes($item);
        if (count($types) > 1) {
          $search['type'] = $types;
        }
        $type = $item->getSetting();
        break;
      
        default:
        # code...
        break;
    }

    return $this->render('merit/index.html.twig', [
      'type' => $type,
      'merits' => $item->getMerits(),
      'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }

  /**
   * @Route("/new", name="merit_new", methods={"GET", "POST"})
   */
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $merit = new Merit();
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($merit);
      $entityManager->flush();

      return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('merit/new.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}", name="merit_show", methods={"GET"})
   */
  public function show(Merit $merit): Response
  {
    return $this->render('merit/show.html.twig', [
      'merit' => $merit,
    ]);
  }

  /**
   * @Route("/{id}/edit", name="merit_edit", methods={"GET", "POST"})
   */
  public function edit(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('merit/edit.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}/translate/{language}", name="merit_translate", methods={"GET", "POST"})
   */
  public function translate(Request $request, Merit $merit, $language, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);
    $merit->setTranslatableLocale($language); // change locale
    // dd($merit);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($merit);
      $entityManager->flush();

      return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('merit/edit.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  /**
   * @Route("/{id}", name="merit_delete", methods={"POST"})
   */
  public function delete(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $merit->getId(), $request->request->get('_token'))) {
      $entityManager->remove($merit);
      $entityManager->flush();
    }

    return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
  }
}
