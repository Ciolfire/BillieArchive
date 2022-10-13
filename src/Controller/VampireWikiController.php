<?php

namespace App\Controller;

use App\Controller\WikiController as WikiController;
use App\Entity\Clan;
use App\Entity\Discipline;
use App\Repository\ClanRepository;
use App\Repository\DisciplineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VampireWikiController extends WikiController {
  /**
   * @Route("/clan", name="clan_index", methods={"GET"})
   */
  public function clans(ClanRepository $clanRepository): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $clanRepository->findAll(),
      'entity' => 'clan',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  /**
   * @Route("/clan/{id}/edit", name="clan_edit", methods={"GET", "POST"})
   */
  public function clanEdit(Request $request, Clan $clan, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(ClanType::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  /**
   * @Route("/clan/new", name="clan_new", methods={"GET", "POST"})
   */
  public function clanNew(Request $request, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $clan = new Clan();

    $form = $this->createForm(ClanType::class, $clan);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($clan);
      $entityManager->flush();
      return $this->redirectToRoute('clan_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'clan',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }

  /**
   * @Route("/discipline", name="discipline_index", methods={"GET"})
   */
  public function disciplines(DisciplineRepository $disciplineRepository): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $disciplineRepository->findAll(),
      'entity' => 'discipline',
      'category' => 'character',
      'type' => 'vampire',
    ]);
  }

  /**
   * @Route("/discipline/{id}/edit", name="discipline_edit", methods={"GET", "POST"})
   */
  public function disciplineEdit(Request $request, Discipline $discipline, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(disciplineType::class, $discipline);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('discipline_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'discipline',
      'form' => $form,
      'type' => 'vampire',
    ]);
  }
}