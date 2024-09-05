<?php

namespace App\Controller;

use App\Entity\Arcanum;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArcanumController extends AbstractController
{
  private DataService $dataService;
  private MageService $service;

  public function __construct(DataService $dataService, MageService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/arcana', name: 'arcanum_index')]
  public function index(): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findAll(Arcanum::class),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}', name: 'arcanum_show')]
  public function show(): Response
  {
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findAll(Arcanum::class),
    ]);
  }
}
