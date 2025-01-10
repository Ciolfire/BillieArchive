<?php declare(strict_types=1);

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

#[Route("{_locale<%supported_locales%>?%default_locale%}/wiki/roll")]
class RollController extends AbstractController
{
  private DataService $dataService;

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

      $this->addFlash('success', ["general.new.done", ['%name%' => $roll->getName()]]);
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
    return $this->render('roll/show.html.twig', [
      'element' => 'roll',
      'roll' => $roll,
    ]);
  }

  #[Route("/{id<\d+>}/fetch", name:"roll_fetch", methods:["GET"])]
  public function fetch(Roll $roll): Response
  {
    return $this->render('roll/_element.html.twig', [
      'element' => 'roll',
      'roll' => $roll,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"roll_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Roll $roll): Response
  {
    $form = $this->createForm(RollType::class, $roll);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($roll);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $roll->getName()]]);
      return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'roll',
      'entity' => $roll,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"roll_delete", methods:["POST"])]
  public function delete(Request $request, Roll $roll): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $token = $request->request->get('_token');
    if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $roll->getId(), $token)) {
      $this->dataService->remove($roll);
      $this->addFlash('success', ["general.delete.done", ['%name%' => $roll->getName()]]);
    }

    return $this->redirectToRoute('roll_list', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("/{setting}", name: "roll_list", methods: ["GET"])]
  public function list(string $setting = null) : Response
  {
    if (is_null($setting)) {
      $rolls = $this->dataService->findBy(Roll::class, [], ['name' => 'ASC']);
      $setting = "human";
    } else {
      $rolls = $this->dataService->findBy(Roll::class, ['type' => $setting], ['name' => 'ASC']);
    }

    return $this->render('roll/list.html.twig', [
      'setting' => $setting,
      'rolls' => $rolls,
      // 'description' => $this->dataService->findOneBy(Description::class, ['name' => 'roll']),
      // 'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }
}
