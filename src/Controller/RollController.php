<?php

namespace App\Controller;

use App\Entity\Roll;
// use App\Entity\Description;
use App\Form\RollType;

use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/roll")]
class RollController extends AbstractController
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/new", name:"roll_new", methods:["GET", "POST"])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $roll = new Roll();

    $form = $this->createForm(RollType::class, $roll);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($roll);
      $entityManager->flush();

      return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'roll',
      'entity' => $roll,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name:"roll_show", methods:["GET"])]
  public function show(Roll $roll): Response
  {
    return $this->render('element/show.html.twig', [
      'element' => 'roll',
      'entity' => $roll,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"roll_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Roll $roll, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(RollType::class, $roll);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'roll',
      'entity' => $roll,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/translate/{language}", name:"roll_translate", methods:["GET", "POST"])]
  public function translate(Request $request, Roll $roll, $language, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(RollType::class, $roll);
    $form->handleRequest($request);
    $roll->setTranslatableLocale($language); // change locale

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($roll);
      $entityManager->flush();

      return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'roll',
      'entity' => $roll,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"roll_delete", methods:["POST"])]
  public function delete(Request $request, Roll $roll, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $roll->getId(), $request->request->get('_token'))) {
      $entityManager->remove($roll);
      $entityManager->flush();
    }

    return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("/{type}/{id<\d+>}", name: "roll_list", methods: ["GET"])]
  public function list($type = null, $id = null)
  {
    if (is_null($type)) {
      $rolls = $this->dataService->findBy(Roll::class, [], ['name' => 'ASC']);
      $type = "human";
    } else {
      $rolls = $this->dataService->findBy(Roll::class, ['type' => $type], ['name' => 'ASC']);
    }

    return $this->render('roll/list.html.twig', [
      'type' => $type,
      'rolls' => $rolls,
      // 'description' => $this->dataService->findOneBy(Description::class, ['name' => 'roll']),
      // 'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }
}
