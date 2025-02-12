<?php

namespace App\Controller;

use App\Entity\Arcanum;
use App\Form\ArcanumType;
use App\Service\DataService;
use App\Service\MageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
      'arcana' => $this->dataService->findBy(Arcanum::class, [], ['name' => 'ASC']),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}', name: 'arcanum_show')]
  public function show(Arcanum $arcanum): Response
  {
    dd($arcanum);
    return $this->render('mage/arcanum/index.html.twig', [
      'arcana' => $this->dataService->findAll(Arcanum::class),
    ]);
  }

  #[Route('/arcanum/{id<\d+>}/edit', name: 'arcanum_edit', methods:["GET", "POST"])]
  public function edit(Request $request, Arcanum $arcanum): Response
  {
    $form = $this->createForm(ArcanumType::class, $arcanum);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($arcanum);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $arcanum->getName()]]);
      return $this->redirectToRoute('arcanum_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'setting' => 'mage',
      'element' => 'arcanum',
      'entity' => $arcanum,
      'form' => $form,
    ]);
  }
}
