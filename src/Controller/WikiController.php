<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Clan;
use App\Entity\Skill;
use App\Form\AttributeType;
use App\Form\ClanType;
use App\Form\SkillType;
use App\Repository\AttributeRepository;
use App\Repository\ClanRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/wiki")
 */
class WikiController extends AbstractController
{
  /**
   * @Route("/", name="wiki_index", methods={"GET"})
   */
  public function index(): Response
  {
    return $this->render('wiki/index.html.twig', [
      'type' => 'human',
    ]);
  }

  /**
   * @Route("/attribute", name="attribute_index", methods={"GET"})
   */
  public function attributes(AttributeRepository $attributeRepository): Response
  {
    
    return $this->render('wiki/list.html.twig', [
      'elements' => $attributeRepository->findAll(),
      'entity' => 'attribute',
      'category' => 'character',
      'type' => 'human',
    ]);
  }

  /**
   * @Route("/attribute/{id}/edit", name="attribute_edit", methods={"GET", "POST"})
   */
  public function attributeEdit(Request $request, Attribute $attribute, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(AttributeType::class, $attribute);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('attribute_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'attribute',
      'form' => $form,
    ]);
  }

  /**
   * @Route("/skill/{id}/edit", name="skill_edit", methods={"GET", "POST"})
   */
  public function skillEdit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    
    $form = $this->createForm(SkillType::class, $skill);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('skill_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('wiki/edit.html.twig', [
      'entity' => 'attribute',
      'form' => $form,
    ]);
  }

  /**
   * @Route("/skill", name="skill_index", methods={"GET"})
   */
  public function skills(SkillRepository $skillRepository): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $skillRepository->findAll(),
      'entity' => 'skill',
      'category' => 'character',
      'type' => 'human',
    ]);
  }

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
}