<?php

namespace App\Controller;

use App\Entity\Rule;
use App\Entity\Description;
use App\Form\RuleType;

use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/rule")]
class RuleController extends AbstractController
{
  private $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/new", name:"rule_new", methods:["GET", "POST"])]
  public function new(Request $request, EntityManagerInterface $entityManager): Response
  {
    $rule = new Rule();

    $form = $this->createForm(RuleType::class, $rule);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($rule);
      $entityManager->flush();

      return $this->redirectToRoute('rule_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'rule',
      'entity' => $rule,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name:"rule_show", methods:["GET"])]
  public function show(Rule $rule): Response
  {
    return $this->render('rule/show.html.twig', [
      'rule' => $rule,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"rule_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Rule $rule, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(RuleType::class, $rule);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('rule_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'rule',
      'entity' => $rule,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/translate/{language}", name:"rule_translate", methods:["GET", "POST"])]
  public function translate(Request $request, Rule $rule, $language, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(RuleType::class, $rule);
    $form->handleRequest($request);
    $rule->setTranslatableLocale($language); // change locale

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($rule);
      $entityManager->flush();

      return $this->redirectToRoute('rule_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'rule',
      'entity' => $rule,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"rule_delete", methods:["POST"])]
  public function delete(Request $request, Rule $rule, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    if ($this->isCsrfTokenValid('delete' . $rule->getId(), $request->request->get('_token'))) {
      $entityManager->remove($rule);
      $entityManager->flush();
    }

    return $this->redirectToRoute('rule_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("/{type}/{id<\d+>}", name: "rule_index", methods: ["GET"])]
  public function list($type = null, $id = null)
  {
    if (is_null($type)) {
      $rules = $this->dataService->findBy(Rule::class, ['parentRule' => null], ['title' => 'ASC']);
      $type = "human";
    } else {
      $rules = $this->dataService->findBy(Rule::class, ['parentRule' => null, 'type' => $type], ['title' => 'ASC']);
    }

    return $this->render('rule/list.html.twig', [
      'type' => $type,
      'rules' => $rules,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'rule']),
      // 'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }
}
