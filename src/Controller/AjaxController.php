<?php

namespace App\Controller;

use App\Service\DataService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/ajax')]
class AjaxController extends AbstractController
{
  private $dataService;


  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }


  #[Route('/load/prerequisites', name: 'a_load_prerequisites', methods: ['GET', 'POST'])]
  public function loadPrerequisites(Request $request): JsonResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());

      $choices = $this->render('forms/choices.html.twig', [
        'choices' => $this->dataService->findAll($data->value),
      ])->getContent();
      return new JsonResponse(['choices' => $choices]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}