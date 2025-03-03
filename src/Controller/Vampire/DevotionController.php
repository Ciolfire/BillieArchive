<?php declare(strict_types=1);

namespace App\Controller\Vampire;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Clan;
use App\Entity\Description;
use App\Entity\Devotion;
use App\Entity\Discipline;
use App\Form\Vampire\DevotionType;
use App\Service\DataService;
use App\Service\VampireService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class DevotionController extends AbstractController
{
  private DataService $dataService;
  private VampireService $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }
  
  #[Route('/wiki/devotions', name: 'vampire_devotion_index', methods: ['GET'])]
  public function disciplines(): Response
  {
    $devotions = $this->dataService->findBy(Devotion::class, [], ['name' => 'ASC']);

    /** @var Devotion $devotion */
    foreach ($devotions as $devotion) {
      $this->dataService->loadPrerequisites($devotion);
    }

    $search = [];
    $search['discipline'] = [];
    foreach ($devotions as $devotion) {
      // Prerequisites
      $prerequisites = $devotion->getPrerequisites();
      foreach ($prerequisites as $prerequisite) {
        if ($prerequisite->getEntity() instanceof Discipline) {
          $discipline = $prerequisite->getEntity();
          $search['discipline'][$discipline->getId()] = $discipline->getName();
        }
      }
      // Clan
      $clan = $devotion->getBloodline();
      if ($clan instanceof Clan) {
        $search['clan'][$clan->getId()] = $clan->getName();
      }
    }
    sort($search['discipline']);
    sort($search['clan']);

    return $this->render('vampire/devotion/index.html.twig', [
      'devotions' => $devotions,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character',
      'search' => $search,
    ]);
  }

  #[Route("/wiki/devotions/list/{filter<\w+>}/{id<\d+>}", name: "vampire_devotion_list", methods: ["GET"])]
  public function devotionList(string $filter, int $id): Response
  {
    $devotions = $this->dataService->getList($filter, $id, Devotion::class, 'getDevotions');
    foreach ($devotions as $devotion) {
      $this->dataService->loadPrerequisites($devotion);
    }

    return $this->render('vampire/devotion/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'devotion']),
      'entity' => 'devotion',
      'category' => 'character',
      'devotions' => $devotions,
      'filter' => $filter,
      'id' => $id,
      'search' => [],
    ]);
  }

  #[Route('/wiki/devotion/{id<\d+>}', name: 'vampire_devotion_show', methods: ['GET', 'POST'])]
  public function devotionShow(Devotion $devotion): Response
  {
    $this->dataService->loadPrerequisites($devotion);

    return $this->render('vampire/devotion/show.html.twig', [
      'devotion' => $devotion,
    ]);
  }

  #[Route('/devotion/new', name: 'vampire_devotion_new', methods: ['GET', 'POST'])]
  public function devotionNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $devotion = new Devotion();
    $form = $this->createForm(DevotionType::class, $devotion);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($devotion);

      $this->addFlash('success', ["general.new.done", ['%name%' => $devotion->getName()]]);
      return $this->redirectToRoute('vampire_devotion_show', ['id' => $devotion->getId()]);
    }

    return $this->render('vampire/devotion/form.html.twig', [
      'action' => 'new',
      'form' => $form
    ]);
  }

  #[Route('/devotion/{id<\d+>}/edit', name: 'vampire_devotion_edit', methods: ['GET', 'POST'])]
  public function devotionEdit(Devotion $devotion, Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(DevotionType::class, $devotion);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($devotion);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $devotion->getName()]]);
      return $this->redirectToRoute('vampire_devotion_show', ['id' => $devotion->getId()]);
    }

    return $this->render('vampire/devotion/form.html.twig', [
      'action' => 'edit',
      'form' => $form
    ]);
  }

  #[Route('/devotion/{id<\d+>}/delete', name: 'vampire_devotion_delete', methods: ['GET'])]
  public function delete(Devotion $devotion): Response
  {
    $this->denyAccessUnlessGranted('delete', $devotion);

    try {
      $this->dataService->remove($devotion);
      $this->addFlash('success', ["devotion.delete.success", ['%name%' => $devotion->getName()]]);

      return $this->redirectToRoute('vampire_devotion_index');
    } catch (\Throwable $th) {
      $this->addFlash('error', ["devotion.delete.failed", ['%name%' => $devotion->getName()]]);
    }


    return $this->redirectToRoute('vampire_devotion_show', ['id' => $devotion->getId()]);
  }
}
