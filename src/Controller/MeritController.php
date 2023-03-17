<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Description;
use App\Entity\Merit;
use App\Form\MeritType;

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
  private $dataService;
  private $categories = ['mental', 'physical', 'social'];

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/new", name: "merit_new", methods: ["GET", "POST"])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $merit = new Merit();

    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($merit);
      $entityManager->flush();

      return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('merit/new.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name: "merit_show", methods: ["GET"])]
  public function show(Merit $merit): Response
  {
    return $this->render('merit/show.html.twig', [
      'merit' => $merit,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name: "merit_edit", methods: ["GET", "POST"])]
  public function edit(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('merit/edit.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/translate/{language}", name: "merit_translate", methods: ["GET", "POST"])]
  public function translate(Request $request, Merit $merit, $language, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);
    $merit->setTranslatableLocale($language); // change locale
    // dd($merit);
    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($merit);
      $entityManager->flush();

      return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('merit/edit.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name: "merit_delete", methods: ["POST"])]
  public function delete(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $merit->getId(), $request->request->get('_token'))) {
      $entityManager->remove($merit);
      $entityManager->flush();
    }

    return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("/{type}/{id<\d+>}", name: "merit_list", methods: ["GET"])]
  public function list($type = null, $id = null)
  {
    $chronicle = false;
    $search = ['category' => $this->categories];
    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        if ($item instanceof Book) {
          $type = $item->getSetting();
          $merits = $item->getMerits();
          // We get the type of book for the search filters
          $types = $this->dataService->getMeritTypes($item);
          if (count($types) > 1) {
            $search['type'] = $types;
          }
        }
        break;
      case 'chronicle':
        /** @var Chronicle */
        $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
        if ($item instanceof Chronicle) {
          $chronicle = $id;
          $type = $item->getType();
          $merits = $item->getMerits();
          // We get the type of book for the search filters
          // $types = $this->dataService->getMeritTypes($item);
          // if (count($types) > 1) {
          //   $search['type'] = $types;
          // }
        }
        break;
      default:
        $merits = $this->dataService->findBy(Merit::class, [], ['name' => 'ASC']);
        $search['type'] = $this->dataService->getMeritTypes();
        $type = "human";
        break;
    }
    foreach ($merits as $merit) {
      /** @var Merit $merit */
      foreach ($merit->getprerequisites() as $prerequisite) {
        $prerequisite->setEntity($this->dataService->findOneBy($prerequisite->getType(), ['id' => $prerequisite->getEntityId()]));
      }
    }

    return $this->render('merit/list.html.twig', [
      'type' => $type,
      'merits' => $merits,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'merit']),
      'search' => $search, // Kinda want to replace for dynamic list
      'chronicle' => $chronicle,
    ]);
  }
}
